<?php

declare(strict_types=1);

namespace App\Controllers\Citizen;

use App\Core\Controller;
use App\Core\Database;

class CheckStatusController extends Controller
{
    public function index(): void
    {
        $this->check();
    }

    public function check(): void
    {
        $db = Database::connection();
        $searchNid = trim((string) ($_POST['nid_number'] ?? $_GET['nid'] ?? $_GET['nid_number'] ?? ''));
        $citizen = null;
        $stages = [];
        $isAjaxRequest = ($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'XMLHttpRequest'
            || (isset($_SERVER['HTTP_ACCEPT']) && str_contains(strtolower((string) $_SERVER['HTTP_ACCEPT']), 'application/json'));

        if ($searchNid !== '') {
            $stmt = $db->prepare(
                'SELECT c.*, ni.national_id_number, d.name AS district_name
                 FROM citizens c
                 INNER JOIN national_ids ni ON ni.citizen_id = c.id
                 LEFT JOIN districts d ON d.id = c.district_of_birth
                 WHERE ni.national_id_number = :nid
                 LIMIT 1'
            );
            $stmt->execute([':nid' => $searchNid]);
            $citizen = $stmt->fetch();

            if (!$citizen) {
                $legacyStmt = $db->prepare(
                    'SELECT c.*, d.name AS district_name FROM citizens c LEFT JOIN districts d ON d.id = c.district_of_birth WHERE c.national_registration_number = :nid LIMIT 1'
                );
                $legacyStmt->execute([':nid' => $searchNid]);
                $citizen = $legacyStmt->fetch();
            }

            if ($citizen) {
                $stages = $this->buildStages($citizen);
            }
        }

        if ($isAjaxRequest) {
            $this->json([
                'success' => $citizen !== null,
                'query' => $searchNid,
                'message' => $citizen !== null
                    ? 'Citizen record found.'
                    : 'No citizen record matched this NID number.',
                'citizen' => $citizen,
                'stages' => $stages,
            ]);
        }

        $this->view('citizen/public_status', [
            'pageTitle' => 'Check NID Status',
            'query' => $searchNid,
            'citizen' => $citizen,
            'stages' => $stages,
        ]);
    }

    private function buildStages(array $citizen): array
    {
        $currentStatus = strtolower((string) ($citizen['current_status'] ?? 'active'));

        $timeline = [
            [
                'label' => 'Registration Submitted',
                'status' => 'completed',
                'date' => $citizen['registration_date'] ?? $citizen['created_at'] ?? date('Y-m-d H:i:s'),
                'description' => 'Citizen information was successfully submitted and recorded in the registration system.',
            ],
            [
                'label' => 'Preview Approval',
                'status' => in_array($currentStatus, ['approved', 'preview_approved', 'printed', 'active'], true) ? 'completed' : 'pending',
                'date' => in_array($currentStatus, ['approved', 'preview_approved', 'printed', 'active'], true)
                    ? 'Approved by the review team'
                    : 'Awaiting review',
                'description' => 'Submitted details are checked to confirm all information is complete and valid.',
            ],
            [
                'label' => 'NID Printing',
                'status' => in_array($currentStatus, ['printed', 'issued', 'active'], true) ? 'completed' : 'pending',
                'date' => in_array($currentStatus, ['printed', 'issued', 'active'], true)
                    ? 'Approved for printing'
                    : 'Awaiting printing approval',
                'description' => 'The approved citizen record is prepared and queued for card generation and issuance.',
            ],
        ];

        return $timeline;
    }
}
