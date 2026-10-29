<?php
declare(strict_types=1);

namespace App\Controllers\Citizen;

use App\Core\Controller;
use App\Core\Database;
use App\Security\Audit;

class CitizenController extends Controller
{
    private function logRegistrationIssue(string $context, \Throwable $exception, array $extra = []): void
    {
        $logData = [
            'timestamp' => date('c'),
            'context' => $context,
            'request_method' => $_SERVER['REQUEST_METHOD'] ?? null,
            'request_uri' => $_SERVER['REQUEST_URI'] ?? null,
            'http_referer' => $_SERVER['HTTP_REFERER'] ?? null,
            'x_requested_with' => $_SERVER['HTTP_X_REQUESTED_WITH'] ?? null,
            'http_accept' => $_SERVER['HTTP_ACCEPT'] ?? null,
            'remote_addr' => $_SERVER['REMOTE_ADDR'] ?? null,
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null,
            'post_keys' => array_keys($_POST),
            'post_values' => array_map(static fn ($value) => is_array($value) ? $value : (string) $value, $_POST),
            'files' => array_map(static fn ($file) => [
                'name' => $file['name'] ?? null,
                'type' => $file['type'] ?? null,
                'size' => $file['size'] ?? null,
                'error' => $file['error'] ?? null,
            ], $_FILES),
            'message' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString(),
            'extra' => $extra,
        ];

        $json = json_encode($logData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        if ($json === false) {
            $json = json_encode([
                'timestamp' => date('c'),
                'context' => $context,
                'message' => $exception->getMessage(),
                'extra' => $extra,
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        }

        error_log('[NRB Citizen Registration] ' . $json);

        $logPath = dirname(__DIR__, 2) . '/logs/citizen_registration.log';
        $logDir = dirname($logPath);
        if (!is_dir($logDir)) {
            mkdir($logDir, 0777, true);
        }

        file_put_contents($logPath, '[' . date('Y-m-d H:i:s') . '] ' . $json . PHP_EOL, FILE_APPEND | LOCK_EX);
    }

    private function ensureBiometricTables(): void
    {
        $db = Database::connection();

        $db->exec(
            'CREATE TABLE IF NOT EXISTS citizen_photos (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                citizen_id BIGINT UNSIGNED NOT NULL,
                file_path VARCHAR(255) NOT NULL,
                file_name VARCHAR(255) NOT NULL,
                mime_type VARCHAR(100) DEFAULT NULL,
                capture_method ENUM("manual_upload", "mobile_capture", "scanner") DEFAULT "manual_upload",
                quality_status ENUM("clear", "needs_review", "missing") DEFAULT "clear",
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                CONSTRAINT fk_citizen_photo FOREIGN KEY (citizen_id) REFERENCES citizens(id) ON DELETE CASCADE
            ) ENGINE=InnoDB'
        );

        $db->exec(
            'CREATE TABLE IF NOT EXISTS citizen_fingerprints (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                citizen_id BIGINT UNSIGNED NOT NULL,
                file_path VARCHAR(255) NOT NULL,
                file_name VARCHAR(255) NOT NULL,
                mime_type VARCHAR(100) DEFAULT NULL,
                fingerprint_type VARCHAR(50) NOT NULL DEFAULT "right_thumb",
                capture_method ENUM("manual_upload", "mobile_capture", "scanner") DEFAULT "manual_upload",
                quality_status ENUM("clear", "needs_review", "missing") DEFAULT "clear",
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                CONSTRAINT fk_citizen_fingerprint FOREIGN KEY (citizen_id) REFERENCES citizens(id) ON DELETE CASCADE
            ) ENGINE=InnoDB'
        );
    }

    private function normalizeCaptureMethod(?string $value): string
    {
        $normalized = strtolower(trim((string) ($value ?? '')));

        if (in_array($normalized, ['manual_upload', 'mobile_capture', 'scanner'], true)) {
            return $normalized;
        }

        if ($normalized === 'camera') {
            return 'mobile_capture';
        }

        return 'manual_upload';
    }

    private function findDuplicateCitizen(string $firstName, string $surname, string $dateOfBirth, string $gender): ?array
    {
        $db = Database::connection();
        $stmt = $db->prepare(
            'SELECT id, national_registration_number FROM citizens WHERE LOWER(first_name) = LOWER(:first_name) AND LOWER(surname) = LOWER(:surname) AND date_of_birth = :date_of_birth AND LOWER(gender) = LOWER(:gender) LIMIT 1'
        );
        $stmt->execute([
            ':first_name' => trim($firstName),
            ':surname' => trim($surname),
            ':date_of_birth' => $dateOfBirth,
            ':gender' => trim($gender),
        ]);

        return $stmt->fetch() ?: null;
    }

    private function generateUniqueNationalRegistrationNumber(?string $providedNumber = null): string
    {
        $db = Database::connection();
        $candidate = trim((string) ($providedNumber ?? ''));

        if ($candidate !== '') {
            $stmt = $db->prepare('SELECT id FROM citizens WHERE national_registration_number = :nrn LIMIT 1');
            $stmt->execute([':nrn' => $candidate]);
            if ($stmt->fetch() === false) {
                return $candidate;
            }
        }

        $alphabet = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        $maxAttempts = 25;

        for ($attempt = 0; $attempt < $maxAttempts; $attempt++) {
            $randomSuffix = '';
            for ($i = 0; $i < 8; $i++) {
                $randomSuffix .= $alphabet[random_int(0, strlen($alphabet) - 1)];
            }

            $value = 'NRN-' . $randomSuffix;
            $stmt = $db->prepare('SELECT id FROM citizens WHERE national_registration_number = :nrn LIMIT 1');
            $stmt->execute([':nrn' => $value]);
            if ($stmt->fetch() === false) {
                return $value;
            }
        }

        throw new \RuntimeException('Unable to generate a unique national registration number after several attempts.');
    }

    private function saveUploadedFile(?array $file, string $targetFolder, array $allowedExtensions, int $maxSizeBytes = 2097152): ?array
    {
        if ($file === null || !isset($file['tmp_name']) || $file['tmp_name'] === '' || !is_uploaded_file($file['tmp_name'])) {
            return null;
        }

        if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            return null;
        }

        if ((int) $file['size'] <= 0 || (int) $file['size'] > $maxSizeBytes) {
            return null;
        }

        $extension = strtolower(pathinfo((string) $file['name'], PATHINFO_EXTENSION));
        if (!in_array($extension, $allowedExtensions, true)) {
            return null;
        }

        $baseDir = dirname(__DIR__, 2) . '/storage/uploads/' . $targetFolder;
        if (!is_dir($baseDir)) {
            mkdir($baseDir, 0777, true);
        }

        $fileName = uniqid('nrb_', true) . '.' . $extension;
        $targetPath = $baseDir . '/' . $fileName;

        if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
            return null;
        }

        return [
            'path' => 'storage/uploads/' . $targetFolder . '/' . $fileName,
            'name' => $fileName,
            'mime' => mime_content_type($targetPath) ?: 'application/octet-stream',
        ];
    }

    public function index(): void
    {
        $db = Database::connection();

        $page = max(1, (int) ($_GET['page'] ?? 1));
        $limit = 10;
        $offset = ($page - 1) * $limit;
        $search = trim((string) ($_GET['search'] ?? ''));

        $where = [];
        $params = [];

        if ($search !== '') {
            $where[] = '(LOWER(first_name) LIKE LOWER(:search) OR LOWER(surname) LIKE LOWER(:search) OR LOWER(national_registration_number) LIKE LOWER(:search) OR LOWER(place_of_birth) LIKE LOWER(:search))';
            $params[':search'] = '%' . $search . '%';
        }

        $whereSql = $where !== [] ? ' WHERE ' . implode(' AND ', $where) : '';

        $countSql = 'SELECT COUNT(*) FROM citizens' . $whereSql;
        $countStmt = $db->prepare($countSql);
        $countStmt->execute($params);
        $totalRecords = (int) $countStmt->fetchColumn();
        $totalPages = max(1, (int) ceil($totalRecords / $limit));

        $rowsSql = 'SELECT c.*, d.name AS district_name FROM citizens c LEFT JOIN districts d ON d.id = c.district_of_birth' . $whereSql . ' ORDER BY c.created_at DESC LIMIT :limit OFFSET :offset';
        $rowsStmt = $db->prepare($rowsSql);
        foreach ($params as $key => $value) {
            $rowsStmt->bindValue($key, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }
        $rowsStmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $rowsStmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $rowsStmt->execute();
        $citizens = $rowsStmt->fetchAll();

        $this->view('citizen/index', [
            'pageTitle' => 'Citizen Registry',
            'citizens' => $citizens,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalRecords' => $totalRecords,
            'search' => $search,
        ]);
    }

    public function create(): void
    {
        $this->view('citizen/create', [
            'pageTitle' => 'Register Citizen',
        ]);
    }

    public function checkNid(): void
    {
        $db = Database::connection();
        $searchNid = trim((string) ($_POST['nid_number'] ?? $_GET['nid'] ?? ''));
        $citizen = null;
        $stages = [];
        $isAjaxRequest = ($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'XMLHttpRequest'
            || (isset($_SERVER['HTTP_ACCEPT']) && str_contains(strtolower((string) $_SERVER['HTTP_ACCEPT']), 'application/json'));

        if ($searchNid !== '') {
            $stmt = $db->prepare(
                'SELECT c.*, d.name AS district_name FROM citizens c LEFT JOIN districts d ON d.id = c.district_of_birth WHERE c.national_registration_number = :nid LIMIT 1'
            );
            $stmt->execute([':nid' => $searchNid]);
            $citizen = $stmt->fetch();

            if ($citizen) {
                $currentStatus = strtolower((string) ($citizen['current_status'] ?? 'registered'));
                $timeline = [
                    [
                        'label' => 'Registration Submitted',
                        'status' => 'completed',
                        'date' => $citizen['registration_date'] ?? $citizen['created_at'] ?? date('Y-m-d H:i:s'),
                        'description' => 'Citizen information was successfully submitted and recorded in the registration system.',
                    ],
                    [
                        'label' => 'Preview Approval',
                        'status' => $currentStatus === 'approved' || $currentStatus === 'preview_approved' || $currentStatus === 'printed' ? 'completed' : 'pending',
                        'date' => $currentStatus === 'approved' || $currentStatus === 'preview_approved' || $currentStatus === 'printed' ? 'Approved by the review team' : 'Awaiting review',
                        'description' => 'Submitted details are checked to confirm all information is complete and valid.',
                    ],
                    [
                        'label' => 'NID Printing',
                        'status' => $currentStatus === 'printed' || $currentStatus === 'issued' ? 'completed' : 'pending',
                        'date' => $currentStatus === 'printed' || $currentStatus === 'issued' ? 'Approved for printing' : 'Awaiting printing approval',
                        'description' => 'The approved citizen record is prepared and queued for card generation and issuance.',
                    ],
                ];

                $stages = $timeline;
            }
        }

        if ($isAjaxRequest) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'success' => $citizen !== null,
                'query' => $searchNid,
                'message' => $citizen !== null
                    ? 'Citizen record found.'
                    : 'No citizen record matched this NID number.',
                'citizen' => $citizen,
                'stages' => $stages,
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
            exit;
        }

        $this->view('citizen/public_status', [
            'pageTitle' => 'Check NID Status',
            'query' => $searchNid,
            'citizen' => $citizen,
            'stages' => $stages,
        ]);
    }

    public function status(): void
    {
        $db = Database::connection();
        $citizenId = (int) ($_GET['id'] ?? 0);

        if ($citizenId <= 0) {
            $this->redirect('/citizen');
        }

        $stmt = $db->prepare(
            'SELECT c.*, d.name AS district_name FROM citizens c LEFT JOIN districts d ON d.id = c.district_of_birth WHERE c.id = :id LIMIT 1'
        );
        $stmt->execute([':id' => $citizenId]);
        $citizen = $stmt->fetch();

        if (!$citizen) {
            $this->redirect('/citizen');
        }

        $photoStmt = $db->prepare(
            'SELECT * FROM citizen_photos WHERE citizen_id = :citizen_id ORDER BY created_at DESC LIMIT 1'
        );
        $photoStmt->execute([':citizen_id' => $citizenId]);
        $photo = $photoStmt->fetch();

        $fingerprintStmt = $db->prepare(
            'SELECT * FROM citizen_fingerprints WHERE citizen_id = :citizen_id ORDER BY created_at DESC LIMIT 1'
        );
        $fingerprintStmt->execute([':citizen_id' => $citizenId]);
        $fingerprint = $fingerprintStmt->fetch();

        $stages = [
            [
                'label' => 'Registration Submitted',
                'status' => 'completed',
                'date' => $citizen['registration_date'] ?? $citizen['created_at'] ?? date('Y-m-d H:i:s'),
                'description' => 'Citizen data captured and saved to the database.',
            ],
            [
                'label' => 'Preview Approval',
                'status' => 'pending',
                'date' => 'Awaiting admin review',
                'description' => 'Registrar verifies the submitted citizen record before it moves forward.',
            ],
            [
                'label' => 'NID Printing',
                'status' => 'pending',
                'date' => 'Awaiting printing approval',
                'description' => 'Approved records are prepared for NID card generation and print authorization.',
            ],
        ];

        $this->view('citizen/status', [
            'pageTitle' => 'Citizen Status',
            'citizen' => $citizen,
            'photo' => $photo,
            'fingerprint' => $fingerprint,
            'stages' => $stages,
        ]);
    }

    public function store(): void
    {
        $db = Database::connection();
        $isAjaxRequest = ($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'XMLHttpRequest'
            || (isset($_SERVER['HTTP_ACCEPT']) && str_contains(strtolower((string) $_SERVER['HTTP_ACCEPT']), 'application/json'));

        error_log('[NRB Citizen Registration] Submission started. Method=' . ($_SERVER['REQUEST_METHOD'] ?? 'UNKNOWN') . ' URI=' . ($_SERVER['REQUEST_URI'] ?? 'UNKNOWN'));
        error_log('[NRB Citizen Registration] POST keys=' . json_encode(array_keys($_POST)));
        error_log('[NRB Citizen Registration] FILE keys=' . json_encode(array_keys($_FILES)));

        $firstName = trim((string) ($_POST['first_name'] ?? ''));
        $middleName = trim((string) ($_POST['other_names'] ?? $_POST['middle_name'] ?? ''));
        $surname = trim((string) ($_POST['surname'] ?? ''));
        $dateOfBirth = (string) ($_POST['date_of_birth'] ?? '');

        $gender = strtoupper((string) ($_POST['gender'] ?? 'Other'));
        if (!in_array($gender, ['MALE', 'FEMALE', 'OTHER'], true)) {
            $gender = 'OTHER';
        }

        if ($firstName === '' || $surname === '' || $dateOfBirth === '') {
            if ($isAjaxRequest) {
                http_response_code(422);
                echo json_encode([
                    'success' => false,
                    'message' => 'First name, surname, and date of birth are required.',
                ]);
                exit;
            }

            throw new \InvalidArgumentException('First name, surname, and date of birth are required.');
        }

        $existingCitizen = $this->findDuplicateCitizen($firstName, $surname, $dateOfBirth, $gender === 'MALE' ? 'Male' : ($gender === 'FEMALE' ? 'Female' : 'Other'));
        if ($existingCitizen !== null) {
            if ($isAjaxRequest) {
                http_response_code(409);
                echo json_encode([
                    'success' => false,
                    'message' => 'A citizen with the same name and date of birth already exists.',
                ]);
                exit;
            }

            throw new \RuntimeException('Duplicate citizen detected for the same name and date of birth.');
        }

        $maritalStatus = trim((string) ($_POST['marital_status'] ?? 'Single'));
        $maritalMap = [
            'NEVER MARRIED' => 'Single',
            'SINGLE' => 'Single',
            'MARRIED' => 'Married',
            'DIVORCED' => 'Divorced',
            'WIDOWED' => 'Widowed',
            'SEPARATED' => 'Divorced',
            'ABANDONED' => 'Single',
        ];
        $maritalValue = $maritalMap[strtoupper($maritalStatus)] ?? 'Single';

        $birthDistrictValue = trim((string) ($_POST['birth_district'] ?? ''));
        $districtOfBirth = null;
        if ($birthDistrictValue !== '') {
            $districtStmt = $db->prepare('SELECT id FROM districts WHERE LOWER(name) = LOWER(:name) LIMIT 1');
            $districtStmt->execute([':name' => $birthDistrictValue]);
            $districtRow = $districtStmt->fetch();
            if ($districtRow) {
                $districtOfBirth = (int) $districtRow['id'];
            }
        }

        $data = [
            'national_registration_number' => $this->generateUniqueNationalRegistrationNumber((string) ($_POST['national_registration_number'] ?? '')),
            'first_name' => $firstName,
            'middle_name' => $middleName !== '' ? $middleName : null,
            'surname' => $surname,
            'gender' => $gender === 'MALE' ? 'Male' : ($gender === 'FEMALE' ? 'Female' : 'Other'),
            'date_of_birth' => $_POST['date_of_birth'] ?? date('Y-m-d'),
            'place_of_birth' => $birthDistrictValue !== '' ? $birthDistrictValue : null,
            'nationality' => $_POST['nationality'] ?? 'Malawian',
            'national_origin' => $_POST['national_origin'] ?? $_POST['country'] ?? 'Malawi',
            'marital_status' => $maritalValue,
            'occupation' => $_POST['occupation'] ?? null,
            'education_level' => $_POST['education_level'] ?? null,
            'blood_group' => $_POST['blood_group'] ?? null,
            'disability_status' => $_POST['disability'] ?? null,
            'religion' => $_POST['religion'] ?? null,
            'district_of_birth' => $districtOfBirth,
            'current_status' => 'Active',
            'registration_date' => date('Y-m-d H:i:s'),
            'created_by' => $_SESSION['user']['id'] ?? null,
        ];

        $this->ensureBiometricTables();

        $db->beginTransaction();

        try {
            $describeRows = $db->query('DESCRIBE citizens')->fetchAll();
            $existingColumns = [];
            foreach ($describeRows as $row) {
                $existingColumns[] = $row['Field'];
            }

            $insertData = [];
            foreach ($data as $column => $value) {
                if (in_array($column, $existingColumns, true)) {
                    $insertData[$column] = $value;
                }
            }

            $insertColumns = array_keys($insertData);
            $placeholders = implode(', ', array_fill(0, count($insertColumns), '?'));
            $sql = 'INSERT INTO citizens (' . implode(', ', $insertColumns) . ') VALUES (' . $placeholders . ')';

            $stmt = $db->prepare($sql);
            $stmt->execute(array_values($insertData));
            $citizenId = (int) $db->lastInsertId();

            $passportPhoto = $this->saveUploadedFile(
                $_FILES['passport_photo'] ?? null,
                'photos',
                ['jpg', 'jpeg', 'png'],
                2 * 1024 * 1024
            );

            if ($passportPhoto === null) {
                $passportPhoto = [
                    'path' => 'assets/images/citizen.png',
                    'name' => 'citizen.png',
                    'mime' => 'image/png',
                ];
            }

            $captureMethod = $this->normalizeCaptureMethod($_POST['capture_method'] ?? null);
            $fingerprintFile = $this->saveUploadedFile(
                $_FILES['fingerprint_image'] ?? null,
                'fingerprints',
                ['jpg', 'jpeg', 'png', 'bmp', 'wsq'],
                3 * 1024 * 1024
            );

            $photoStmt = $db->prepare(
                'INSERT INTO citizen_photos (citizen_id, file_path, file_name, mime_type, capture_method, quality_status, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())'
            );
            $photoStmt->execute([
                $citizenId,
                $passportPhoto['path'],
                $passportPhoto['name'],
                $passportPhoto['mime'],
                $captureMethod,
                'clear',
            ]);

            if ($fingerprintFile !== null) {
                $fpStmt = $db->prepare(
                    'INSERT INTO citizen_fingerprints (citizen_id, file_path, file_name, mime_type, fingerprint_type, capture_method, quality_status, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())'
                );
                $fpStmt->execute([
                    $citizenId,
                    $fingerprintFile['path'],
                    $fingerprintFile['name'],
                    $fingerprintFile['mime'],
                    $_POST['fingerprint_type'] ?? 'right_thumb',
                    $captureMethod,
                    'clear',
                ]);
            }

            $db->commit();
        } catch (\Throwable $e) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            $this->logRegistrationIssue('store_failed', $e, [
                'citizen_data' => $data,
                'passport_photo_present' => isset($_FILES['passport_photo']),
                'fingerprint_present' => isset($_FILES['fingerprint_image']),
                'is_ajax_request' => $isAjaxRequest,
            ]);

            if ($isAjaxRequest) {
                http_response_code(500);
                echo json_encode([
                    'success' => false,
                    'message' => 'Citizen registration failed. Please review the form and try again.',
                ]);
                exit;
            }

            throw $e;
        }

        Audit::log('citizen_registered', 'citizen', null, json_encode($data), 'Citizen registration submitted with biometric capture');

        if ($isAjaxRequest) {
            echo json_encode([
                'success' => true,
                'message' => 'Citizen registration completed successfully.',
                'redirect' => route_url('/citizen'),
            ]);
            exit;
        }

        $this->success('Citizen registration completed successfully.');
        $this->redirect('/citizen');
    }
}
