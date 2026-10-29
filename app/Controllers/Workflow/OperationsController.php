<?php
declare(strict_types=1);

namespace App\Controllers\Workflow;

use App\Core\Controller;
use App\Core\Database;
use App\Models\Role;
use App\Models\User;

class OperationsController extends Controller
{
    public function deaths(): void
    {
        $this->renderPage(
            'Death Registration',
            'Track death certificates and post-registration verification.',
            [
                ['reference' => 'DR-2026-011', 'name' => 'Alick Mtinga', 'district' => 'Lilongwe', 'status' => 'Pending'],
                ['reference' => 'DR-2026-012', 'name' => 'Martha Bwanali', 'district' => 'Nkhata Bay', 'status' => 'Approved'],
            ],
            ['Reference', 'Citizen Name', 'District', 'Status'],
            'New Death Record'
        );
    }

    public function applications(): void
    {
        $this->renderPage(
            'Applications',
            'Manage registration and identity applications submitted by citizens.',
            [
                ['reference' => 'APP-2026-034', 'name' => 'Beatrice Soko', 'district' => 'Blantyre', 'status' => 'In Review'],
                ['reference' => 'APP-2026-035', 'name' => 'Kondwani Simbeye', 'district' => 'Kasungu', 'status' => 'Submitted'],
            ],
            ['Application ID', 'Applicant Name', 'District', 'Status'],
            'New Application'
        );
    }

    public function pendingApprovals(): void
    {
        $this->renderPage(
            'Pending Approvals',
            'Review records waiting for verification and authorization.',
            [
                ['reference' => 'APR-2026-045', 'name' => 'Lameck Kafodya', 'district' => 'Machinga', 'status' => 'Awaiting Approval'],
                ['reference' => 'APR-2026-046', 'name' => 'Juma Nkhoma', 'district' => 'Mzimba', 'status' => 'Queued'],
            ],
            ['Approval ID', 'Name', 'District', 'Status'],
            'Review Queue'
        );
    }

    public function reviewApprovals(): void
    {
        $this->renderPage(
            'Approval Review',
            'Monitor records under review by district officers and validators.',
            [
                ['reference' => 'REV-2026-098', 'name' => 'Diana Kanyama', 'district' => 'Lilongwe', 'status' => 'Under Review'],
                ['reference' => 'REV-2026-099', 'name' => 'Felix Gama', 'district' => 'Zomba', 'status' => 'Escalated'],
            ],
            ['Review ID', 'Citizen Name', 'District', 'Status'],
            'Open Review'
        );
    }

    public function approvedApprovals(): void
    {
        $this->renderPage(
            'Approved Records',
            'Review approved citizen and identity actions across the NRB network.',
            [
                ['reference' => 'APR-2026-110', 'name' => 'Chikondi Jere', 'district' => 'Blantyre', 'status' => 'Approved'],
                ['reference' => 'APR-2026-111', 'name' => 'Martin Mponda', 'district' => 'Kasungu', 'status' => 'Approved'],
            ],
            ['Approval ID', 'Name', 'District', 'Status'],
            'View Archive'
        );
    }

    public function printQueue(): void
    {
        $this->renderPage(
            'Print Queue',
            'Queue printed cards and monitor production status.',
            [
                ['reference' => 'PRN-2026-022', 'name' => 'Madalitso Chirwa', 'district' => 'Lilongwe', 'status' => 'Queued'],
                ['reference' => 'PRN-2026-023', 'name' => 'Hastings Luwanda', 'district' => 'Machinga', 'status' => 'Printing'],
            ],
            ['Print ID', 'Citizen Name', 'District', 'Status'],
            'Add to Queue'
        );
    }

    public function printPreview(): void
    {
        $this->renderPage(
            'Card Preview',
            'Review card layout, print quality, and issuance readiness.',
            [
                ['reference' => 'PRE-2026-009', 'name' => 'Nellie Moyo', 'district' => 'Blantyre', 'status' => 'Ready'],
                ['reference' => 'PRE-2026-010', 'name' => 'John Kamwendo', 'district' => 'Mzimba', 'status' => 'Needs Review'],
            ],
            ['Preview ID', 'Citizen Name', 'District', 'Status'],
            'Preview Card'
        );
    }

    public function auditLogs(): void
    {
        $this->renderPage(
            'Audit Logs',
            'Inspect system security events and operational changes.',
            [
                ['reference' => 'AUD-2026-100', 'name' => 'User Role Change', 'district' => 'HQ', 'status' => 'Logged'],
                ['reference' => 'AUD-2026-101', 'name' => 'ID Verification', 'district' => 'Lilongwe', 'status' => 'Logged'],
            ],
            ['Log ID', 'Activity', 'Location', 'Status'],
            'Export Audit Trail'
        );
    }

    public function loginHistory(): void
    {
        $this->renderPage(
            'Login History',
            'Monitor employee access sessions and authentication activity.',
            [
                ['reference' => 'LGN-2026-020', 'name' => 'S. Moyo', 'district' => 'HQ', 'status' => 'Successful'],
                ['reference' => 'LGN-2026-021', 'name' => 'D. Banda', 'district' => 'Blantyre', 'status' => 'Successful'],
            ],
            ['Session ID', 'Officer', 'Location', 'Status'],
            'Download Logs'
        );
    }

    public function settingsGeneral(): void
    {
        $user = $_SESSION['user'] ?? null;
        $roleName = trim((string) ($user['role'] ?? ''));
        $effectivePermissions = $roleName === '' ? [] : Role::effectivePermissions($roleName, Role::defaultPermissionsByRole());

        if ($roleName !== 'Super Administrator' && !in_array('manage_system_settings', $effectivePermissions, true)) {
            $roleDirectoryMap = [
                'Super Administrator' => 'super-admin',
                'National Administrator' => 'national-admin',
                'District Administrator' => 'district-admin',
                'Registration Officer' => 'registration-officer',
                'Supervisor' => 'supervisor',
                'Card Printing Officer' => 'card-printing-officer',
                'Auditor' => 'auditor',
                'Read Only User' => 'read-only-user',
            ];

            $redirectRole = $roleDirectoryMap[$roleName] ?? 'super-admin';
            $_SESSION['error'] = 'Only the Super Administrator can access General Settings.';
            $this->redirect('/' . $redirectRole . '/dashboard');
        }

        $settingsSections = [
            [
                'id' => 'organization',
                'label' => 'Organization',
                'items' => [
                    ['label' => 'Organization Name', 'type' => 'text', 'value' => 'National Registration Bureau'],
                    ['label' => 'Organization Short Name', 'type' => 'text', 'value' => 'NRB'],
                    ['label' => 'Government Ministry', 'type' => 'text', 'value' => 'Ministry of Civic Education and National Unity'],
                    ['label' => 'Department Name', 'type' => 'text', 'value' => 'Department of National Registration and Identity Management'],
                    ['label' => 'Organization Logo', 'type' => 'file', 'value' => ''],
                    ['label' => 'Organization Seal / Emblem', 'type' => 'file', 'value' => ''],
                    ['label' => 'Official Address', 'type' => 'textarea', 'value' => 'P.O. Box 124, Lilongwe 3, Malawi'],
                    ['label' => 'Postal Address', 'type' => 'textarea', 'value' => 'Private Bag 320, Lilongwe 3, Malawi'],
                    ['label' => 'Telephone Number', 'type' => 'tel', 'value' => '+265 1 234 567'],
                    ['label' => 'Mobile Number', 'type' => 'tel', 'value' => '+265 991 234 567'],
                    ['label' => 'Email Address', 'type' => 'email', 'value' => 'info@nrb.gov.mw'],
                    ['label' => 'Website', 'type' => 'url', 'value' => 'https://www.nrb.gov.mw'],
                    ['label' => 'Office Hours', 'type' => 'text', 'value' => 'Monday - Friday, 08:00 - 17:00'],
                ],
            ],
            [
                'id' => 'system',
                'label' => 'System',
                'items' => [
                    ['label' => 'System Name', 'type' => 'text', 'value' => 'Electronic National Registration Bureau System'],
                    ['label' => 'Short System Name', 'type' => 'text', 'value' => 'NRB'],
                    ['label' => 'System Version', 'type' => 'text', 'value' => '2.7.0'],
                    ['label' => 'Environment', 'type' => 'select', 'value' => 'Production', 'options' => ['Development', 'Test', 'Production']],
                    ['label' => 'System Status', 'type' => 'select', 'value' => 'Online', 'options' => ['Online', 'Maintenance']],
                    ['label' => 'Maintenance Message', 'type' => 'textarea', 'value' => 'The NRB system is currently operating normally.'],
                    ['label' => 'Default Landing Page', 'type' => 'select', 'value' => 'Public Landing', 'options' => ['Public Landing', 'Login', 'Dashboard']],
                    ['label' => 'Default Dashboard', 'type' => 'select', 'value' => 'Super Administrator', 'options' => ['Super Administrator', 'National Administrator', 'District Administrator', 'Registration Officer']],
                    ['label' => 'Application Timezone', 'type' => 'select', 'value' => 'Africa/Lilongwe', 'options' => ['Africa/Lilongwe', 'UTC', 'Africa/Blantyre']],
                    ['label' => 'Date Format', 'type' => 'select', 'value' => 'DD/MM/YYYY', 'options' => ['DD/MM/YYYY', 'MM/DD/YYYY', 'YYYY-MM-DD']],
                    ['label' => 'Time Format', 'type' => 'select', 'value' => '24 Hour', 'options' => ['24 Hour', '12 Hour']],
                    ['label' => 'Default Language', 'type' => 'select', 'value' => 'English', 'options' => ['English', 'Chichewa', 'French']],
                    ['label' => 'Currency', 'type' => 'text', 'value' => 'MWK'],
                    ['label' => 'Session Timeout (minutes)', 'type' => 'number', 'value' => '30'],
                ],
            ],
            [
                'id' => 'national-registration',
                'label' => 'National Registration',
                'items' => [
                    ['label' => 'Minimum Registration Age', 'type' => 'number', 'value' => '18'],
                    ['label' => 'Nationality Rules', 'type' => 'textarea', 'value' => 'Malawian nationals and eligible foreign residents under defined legal categories.'],
                    ['label' => 'Duplicate Registration Detection', 'type' => 'select', 'value' => 'Enabled', 'options' => ['Enabled', 'Disabled']],
                    ['label' => 'Auto-generate Citizen Number', 'type' => 'select', 'value' => 'Enabled', 'options' => ['Enabled', 'Disabled']],
                    ['label' => 'Auto-generate Application Number', 'type' => 'select', 'value' => 'Enabled', 'options' => ['Enabled', 'Disabled']],
                    ['label' => 'Auto-generate National ID Number', 'type' => 'select', 'value' => 'Enabled', 'options' => ['Enabled', 'Disabled']],
                    ['label' => 'Registration Workflow Enabled', 'type' => 'select', 'value' => 'Enabled', 'options' => ['Enabled', 'Disabled']],
                    ['label' => 'Supervisor Approval Required', 'type' => 'select', 'value' => 'Required', 'options' => ['Required', 'Optional']],
                    ['label' => 'Require Village Head Recommendation', 'type' => 'select', 'value' => 'Required', 'options' => ['Required', 'Optional']],
                    ['label' => 'Require Witnesses', 'type' => 'select', 'value' => 'Enabled', 'options' => ['Enabled', 'Disabled']],
                    ['label' => 'Require Parent Information', 'type' => 'select', 'value' => 'Enabled', 'options' => ['Enabled', 'Disabled']],
                    ['label' => 'Require Birth Certificate', 'type' => 'select', 'value' => 'Enabled', 'options' => ['Enabled', 'Disabled']],
                    ['label' => 'Allow Draft Applications', 'type' => 'select', 'value' => 'Enabled', 'options' => ['Enabled', 'Disabled']],
                    ['label' => 'Maximum Draft Retention (days)', 'type' => 'number', 'value' => '30'],
                ],
            ],
            [
                'id' => 'national-id',
                'label' => 'National ID',
                'items' => [
                    ['label' => 'ID Number Prefix', 'type' => 'text', 'value' => 'MWI'],
                    ['label' => 'ID Number Length', 'type' => 'number', 'value' => '12'],
                    ['label' => 'Card Expiry Period', 'type' => 'number', 'value' => '10'],
                    ['label' => 'Card Template', 'type' => 'select', 'value' => 'Standard', 'options' => ['Standard', 'Premium', 'Security']],
                    ['label' => 'Card Orientation', 'type' => 'select', 'value' => 'Portrait', 'options' => ['Portrait', 'Landscape']],
                    ['label' => 'QR Code Enabled', 'type' => 'select', 'value' => 'Enabled', 'options' => ['Enabled', 'Disabled']],
                    ['label' => 'Barcode Enabled', 'type' => 'select', 'value' => 'Enabled', 'options' => ['Enabled', 'Disabled']],
                    ['label' => 'Digital Signature Enabled', 'type' => 'select', 'value' => 'Enabled', 'options' => ['Enabled', 'Disabled']],
                    ['label' => 'Card Printing Approval Required', 'type' => 'select', 'value' => 'Required', 'options' => ['Required', 'Optional']],
                    ['label' => 'Card Reprint Limit', 'type' => 'number', 'value' => '2'],
                ],
            ],
            [
                'id' => 'registration-centres',
                'label' => 'Registration Centres',
                'items' => [
                    ['label' => 'Default Registration Centre', 'type' => 'text', 'value' => 'Lilongwe Central Registration Centre'],
                    ['label' => 'Centre Code Format', 'type' => 'text', 'value' => 'RC-{district}-{number}'],
                    ['label' => 'Auto-assign Registration Centre', 'type' => 'select', 'value' => 'Enabled', 'options' => ['Enabled', 'Disabled']],
                    ['label' => 'Centre Operating Hours', 'type' => 'text', 'value' => '08:00 - 17:00'],
                    ['label' => 'Queue Management Enabled', 'type' => 'select', 'value' => 'Enabled', 'options' => ['Enabled', 'Disabled']],
                    ['label' => 'Appointment System Enabled', 'type' => 'select', 'value' => 'Enabled', 'options' => ['Enabled', 'Disabled']],
                ],
            ],
            [
                'id' => 'geography',
                'label' => 'Geography',
                'items' => [
                    ['label' => 'Regions', 'type' => 'select', 'value' => 'Managed', 'options' => ['Managed', 'Not Managed']],
                    ['label' => 'Districts', 'type' => 'select', 'value' => 'Managed', 'options' => ['Managed', 'Not Managed']],
                    ['label' => 'Traditional Authorities', 'type' => 'select', 'value' => 'Managed', 'options' => ['Managed', 'Not Managed']],
                    ['label' => 'Villages', 'type' => 'select', 'value' => 'Managed', 'options' => ['Managed', 'Not Managed']],
                    ['label' => 'Wards', 'type' => 'select', 'value' => 'Managed', 'options' => ['Managed', 'Not Managed']],
                    ['label' => 'Constituencies', 'type' => 'select', 'value' => 'Managed', 'options' => ['Managed', 'Not Managed']],
                ],
            ],
            [
                'id' => 'communications',
                'label' => 'Communications',
                'items' => [
                    ['label' => 'SMTP Host', 'type' => 'text', 'value' => 'smtp.nrb.gov.mw'],
                    ['label' => 'SMTP Port', 'type' => 'number', 'value' => '587'],
                    ['label' => 'SMTP Username', 'type' => 'text', 'value' => 'nrb-system'],
                    ['label' => 'SMTP Password', 'type' => 'password', 'value' => '********'],
                    ['label' => 'Encryption', 'type' => 'select', 'value' => 'TLS', 'options' => ['TLS', 'SSL', 'None']],
                    ['label' => 'Sender Name', 'type' => 'text', 'value' => 'National Registration Bureau'],
                    ['label' => 'Sender Email', 'type' => 'email', 'value' => 'no-reply@nrb.gov.mw'],
                    ['label' => 'Test Email', 'type' => 'email', 'value' => 'admin@nrb.gov.mw'],
                    ['label' => 'SMS Provider', 'type' => 'text', 'value' => 'Twilio'],
                    ['label' => 'API Key', 'type' => 'password', 'value' => '********'],
                    ['label' => 'Sender ID', 'type' => 'text', 'value' => 'NRBMW'],
                    ['label' => 'Test SMS', 'type' => 'text', 'value' => '+265 999 000 000'],
                    ['label' => 'Email Notifications', 'type' => 'select', 'value' => 'Enabled', 'options' => ['Enabled', 'Disabled']],
                    ['label' => 'SMS Notifications', 'type' => 'select', 'value' => 'Enabled', 'options' => ['Enabled', 'Disabled']],
                    ['label' => 'In-System Notifications', 'type' => 'select', 'value' => 'Enabled', 'options' => ['Enabled', 'Disabled']],
                    ['label' => 'Push Notifications', 'type' => 'select', 'value' => 'Enabled', 'options' => ['Enabled', 'Disabled']],
                ],
            ],
            [
                'id' => 'file-uploads',
                'label' => 'File Uploads',
                'items' => [
                    ['label' => 'Maximum Upload Size', 'type' => 'text', 'value' => '10 MB'],
                    ['label' => 'Allowed Image Types', 'type' => 'text', 'value' => 'jpg, jpeg, png, webp'],
                    ['label' => 'Allowed Document Types', 'type' => 'text', 'value' => 'pdf, doc, docx'],
                    ['label' => 'Allowed Video Types', 'type' => 'text', 'value' => 'mp4, mov'],
                    ['label' => 'Maximum Number of Attachments', 'type' => 'number', 'value' => '5'],
                    ['label' => 'Image Compression', 'type' => 'select', 'value' => 'Enabled', 'options' => ['Enabled', 'Disabled']],
                    ['label' => 'Automatic Image Resize', 'type' => 'select', 'value' => 'Enabled', 'options' => ['Enabled', 'Disabled']],
                    ['label' => 'Automatic Image Enhancement', 'type' => 'select', 'value' => 'Enabled', 'options' => ['Enabled', 'Disabled']],
                    ['label' => 'File Storage Location', 'type' => 'text', 'value' => '/storage/uploads'],
                    ['label' => 'Virus Scan on Upload', 'type' => 'select', 'value' => 'Enabled', 'options' => ['Enabled', 'Disabled']],
                ],
            ],
            [
                'id' => 'biometrics',
                'label' => 'Biometrics',
                'items' => [
                    ['label' => 'Fingerprint Capture Enabled', 'type' => 'select', 'value' => 'Enabled', 'options' => ['Enabled', 'Disabled']],
                    ['label' => 'Facial Recognition Enabled', 'type' => 'select', 'value' => 'Enabled', 'options' => ['Enabled', 'Disabled']],
                    ['label' => 'Signature Capture Enabled', 'type' => 'select', 'value' => 'Enabled', 'options' => ['Enabled', 'Disabled']],
                    ['label' => 'Iris Scan Enabled', 'type' => 'select', 'value' => 'Disabled', 'options' => ['Enabled', 'Disabled']],
                    ['label' => 'Webcam Resolution', 'type' => 'text', 'value' => '1920x1080'],
                    ['label' => 'Camera Selection', 'type' => 'text', 'value' => 'Default device'],
                    ['label' => 'Automatic Image Quality Check', 'type' => 'select', 'value' => 'Enabled', 'options' => ['Enabled', 'Disabled']],
                    ['label' => 'Duplicate Fingerprint Detection', 'type' => 'select', 'value' => 'Enabled', 'options' => ['Enabled', 'Disabled']],
                    ['label' => 'Biometric Match Threshold', 'type' => 'number', 'value' => '95'],
                ],
            ],
            [
                'id' => 'workflow',
                'label' => 'Workflow',
                'items' => [
                    ['label' => 'Approval Levels', 'type' => 'number', 'value' => '3'],
                    ['label' => 'Automatic Approval', 'type' => 'select', 'value' => 'Disabled', 'options' => ['Enabled', 'Disabled']],
                    ['label' => 'Supervisor Approval Required', 'type' => 'select', 'value' => 'Required', 'options' => ['Required', 'Optional']],
                    ['label' => 'National Approval Required', 'type' => 'select', 'value' => 'Required', 'options' => ['Required', 'Optional']],
                    ['label' => 'Escalation Rules', 'type' => 'textarea', 'value' => 'Escalate unresolved applications after 72 hours.'],
                    ['label' => 'Application Expiry', 'type' => 'number', 'value' => '90'],
                    ['label' => 'Rejection Reasons', 'type' => 'textarea', 'value' => 'Incomplete documentation, duplicate records, invalid age, missing witness, missing biometric capture.'],
                    ['label' => 'Correction Workflow', 'type' => 'select', 'value' => 'Enabled', 'options' => ['Enabled', 'Disabled']],
                ],
            ],
            [
                'id' => 'printing',
                'label' => 'Printing',
                'items' => [
                    ['label' => 'Default Printer', 'type' => 'text', 'value' => 'NRB-HP-OfficeJet'],
                    ['label' => 'Default Paper Size', 'type' => 'select', 'value' => 'A4', 'options' => ['A4', 'A5', 'Letter']],
                    ['label' => 'Print Resolution', 'type' => 'text', 'value' => '600 DPI'],
                    ['label' => 'Duplex Printing', 'type' => 'select', 'value' => 'Enabled', 'options' => ['Enabled', 'Disabled']],
                    ['label' => 'Card Printer', 'type' => 'text', 'value' => 'Fargo HDP5000'],
                    ['label' => 'Print Preview Enabled', 'type' => 'select', 'value' => 'Enabled', 'options' => ['Enabled', 'Disabled']],
                    ['label' => 'Auto Print After Approval', 'type' => 'select', 'value' => 'Enabled', 'options' => ['Enabled', 'Disabled']],
                    ['label' => 'Printer Queue Timeout', 'type' => 'number', 'value' => '15'],
                ],
            ],
            [
                'id' => 'dashboard',
                'label' => 'Dashboard',
                'items' => [
                    ['label' => 'Dashboard Refresh Interval', 'type' => 'number', 'value' => '60'],
                    ['label' => 'Statistics Refresh', 'type' => 'select', 'value' => 'Enabled', 'options' => ['Enabled', 'Disabled']],
                    ['label' => 'Charts Enabled', 'type' => 'select', 'value' => 'Enabled', 'options' => ['Enabled', 'Disabled']],
                    ['label' => 'Recent Activity Count', 'type' => 'number', 'value' => '10'],
                    ['label' => 'Default Widgets', 'type' => 'text', 'value' => 'Applications, National IDs, Registrations, Alerts'],
                    ['label' => 'Show Online Users', 'type' => 'select', 'value' => 'Enabled', 'options' => ['Enabled', 'Disabled']],
                    ['label' => 'Show Pending Applications', 'type' => 'select', 'value' => 'Enabled', 'options' => ['Enabled', 'Disabled']],
                    ['label' => 'Show Today\'s Registrations', 'type' => 'select', 'value' => 'Enabled', 'options' => ['Enabled', 'Disabled']],
                ],
            ],
            [
                'id' => 'search',
                'label' => 'Search',
                'items' => [
                    ['label' => 'Minimum Search Characters', 'type' => 'number', 'value' => '3'],
                    ['label' => 'Maximum Search Results', 'type' => 'number', 'value' => '50'],
                    ['label' => 'Search by National ID', 'type' => 'select', 'value' => 'Enabled', 'options' => ['Enabled', 'Disabled']],
                    ['label' => 'Search by Name', 'type' => 'select', 'value' => 'Enabled', 'options' => ['Enabled', 'Disabled']],
                    ['label' => 'Search by Fingerprint', 'type' => 'select', 'value' => 'Enabled', 'options' => ['Enabled', 'Disabled']],
                    ['label' => 'Search by QR Code', 'type' => 'select', 'value' => 'Enabled', 'options' => ['Enabled', 'Disabled']],
                    ['label' => 'Fuzzy Search Enabled', 'type' => 'select', 'value' => 'Enabled', 'options' => ['Enabled', 'Disabled']],
                ],
            ],
            [
                'id' => 'reports',
                'label' => 'Reports',
                'items' => [
                    ['label' => 'Default Report Format', 'type' => 'select', 'value' => 'PDF', 'options' => ['PDF', 'Excel', 'CSV']],
                    ['label' => 'Enable PDF Export', 'type' => 'select', 'value' => 'Enabled', 'options' => ['Enabled', 'Disabled']],
                    ['label' => 'Enable Excel Export', 'type' => 'select', 'value' => 'Enabled', 'options' => ['Enabled', 'Disabled']],
                    ['label' => 'Enable CSV Export', 'type' => 'select', 'value' => 'Enabled', 'options' => ['Enabled', 'Disabled']],
                    ['label' => 'Report Watermark', 'type' => 'select', 'value' => 'Enabled', 'options' => ['Enabled', 'Disabled']],
                    ['label' => 'Report Footer', 'type' => 'text', 'value' => 'Generated by the National Registration Bureau'],
                    ['label' => 'Digital Signature on Reports', 'type' => 'select', 'value' => 'Enabled', 'options' => ['Enabled', 'Disabled']],
                    ['label' => 'Automatic Report Numbering', 'type' => 'select', 'value' => 'Enabled', 'options' => ['Enabled', 'Disabled']],
                ],
            ],
            [
                'id' => 'audit',
                'label' => 'Audit',
                'items' => [
                    ['label' => 'Enable Audit Logs', 'type' => 'select', 'value' => 'Enabled', 'options' => ['Enabled', 'Disabled']],
                    ['label' => 'Log Login Events', 'type' => 'select', 'value' => 'Enabled', 'options' => ['Enabled', 'Disabled']],
                    ['label' => 'Log Record Changes', 'type' => 'select', 'value' => 'Enabled', 'options' => ['Enabled', 'Disabled']],
                    ['label' => 'Log Printing', 'type' => 'select', 'value' => 'Enabled', 'options' => ['Enabled', 'Disabled']],
                    ['label' => 'Log Exports', 'type' => 'select', 'value' => 'Enabled', 'options' => ['Enabled', 'Disabled']],
                    ['label' => 'Log Failed Logins', 'type' => 'select', 'value' => 'Enabled', 'options' => ['Enabled', 'Disabled']],
                    ['label' => 'Audit Retention Period', 'type' => 'number', 'value' => '365'],
                    ['label' => 'Export Audit Logs', 'type' => 'select', 'value' => 'Enabled', 'options' => ['Enabled', 'Disabled']],
                ],
            ],
            [
                'id' => 'backup',
                'label' => 'Backup',
                'items' => [
                    ['label' => 'Automatic Backup', 'type' => 'select', 'value' => 'Enabled', 'options' => ['Enabled', 'Disabled']],
                    ['label' => 'Backup Schedule', 'type' => 'text', 'value' => 'Daily at 02:00'],
                    ['label' => 'Backup Location', 'type' => 'text', 'value' => '/storage/backups'],
                    ['label' => 'Retention Period', 'type' => 'number', 'value' => '30'],
                    ['label' => 'Compress Backups', 'type' => 'select', 'value' => 'Enabled', 'options' => ['Enabled', 'Disabled']],
                    ['label' => 'Encrypt Backups', 'type' => 'select', 'value' => 'Enabled', 'options' => ['Enabled', 'Disabled']],
                    ['label' => 'Test Restore', 'type' => 'select', 'value' => 'Enabled', 'options' => ['Enabled', 'Disabled']],
                ],
            ],
            [
                'id' => 'security',
                'label' => 'Security',
                'items' => [
                    ['label' => 'Password Minimum Length', 'type' => 'number', 'value' => '10'],
                    ['label' => 'Password Complexity', 'type' => 'select', 'value' => 'Strong', 'options' => ['Strong', 'Moderate', 'Basic']],
                    ['label' => 'Password Expiry', 'type' => 'number', 'value' => '90'],
                    ['label' => 'Maximum Login Attempts', 'type' => 'number', 'value' => '5'],
                    ['label' => 'Account Lock Duration', 'type' => 'number', 'value' => '30'],
                    ['label' => 'Force Password Change', 'type' => 'select', 'value' => 'Enabled', 'options' => ['Enabled', 'Disabled']],
                    ['label' => 'Two-Factor Authentication Default', 'type' => 'select', 'value' => 'Enabled', 'options' => ['Enabled', 'Disabled']],
                    ['label' => 'CAPTCHA Enabled', 'type' => 'select', 'value' => 'Enabled', 'options' => ['Enabled', 'Disabled']],
                    ['label' => 'CSRF Protection', 'type' => 'select', 'value' => 'Enabled', 'options' => ['Enabled', 'Disabled']],
                    ['label' => 'Session Timeout', 'type' => 'number', 'value' => '30'],
                    ['label' => 'IP Whitelist', 'type' => 'text', 'value' => '10.0.0.0/8, 192.168.0.0/16'],
                    ['label' => 'Allowed Countries', 'type' => 'text', 'value' => 'Malawi'],
                ],
            ],
            [
                'id' => 'branding',
                'label' => 'Branding',
                'items' => [
                    ['label' => 'Login Logo', 'type' => 'file', 'value' => ''],
                    ['label' => 'Dashboard Logo', 'type' => 'file', 'value' => ''],
                    ['label' => 'Favicon', 'type' => 'file', 'value' => ''],
                    ['label' => 'System Theme', 'type' => 'select', 'value' => 'Government', 'options' => ['Government', 'Dark', 'Light']],
                    ['label' => 'Primary Color', 'type' => 'color', 'value' => '#004aad'],
                    ['label' => 'Secondary Color', 'type' => 'color', 'value' => '#17352d'],
                    ['label' => 'Login Background', 'type' => 'file', 'value' => ''],
                    ['label' => 'Footer Text', 'type' => 'text', 'value' => 'National Registration Bureau'],
                    ['label' => 'Copyright Notice', 'type' => 'text', 'value' => '© 2026 National Registration Bureau. All rights reserved.'],
                ],
            ],
            [
                'id' => 'legal',
                'label' => 'Legal',
                'items' => [
                    ['label' => 'Terms and Conditions', 'type' => 'textarea', 'value' => 'This system is governed by the official government digital services policy.'],
                    ['label' => 'Privacy Policy', 'type' => 'textarea', 'value' => 'Personal data is protected under law and used only for registration and identity purposes.'],
                    ['label' => 'Cookie Policy', 'type' => 'textarea', 'value' => 'Cookies are used to preserve session security and improve service reliability.'],
                    ['label' => 'Data Protection Notice', 'type' => 'textarea', 'value' => 'All personal data is handled according to applicable data protection rules.'],
                    ['label' => 'User Agreement', 'type' => 'textarea', 'value' => 'Users must not access the system outside approved authority.'],
                    ['label' => 'Copyright Information', 'type' => 'textarea', 'value' => 'Copyright © Government of Malawi.'],
                ],
            ],
            [
                'id' => 'licensing',
                'label' => 'Licensing',
                'items' => [
                    ['label' => 'License Key', 'type' => 'text', 'value' => 'NRB-ENTERPRISE-2026'],
                    ['label' => 'License Status', 'type' => 'select', 'value' => 'Active', 'options' => ['Active', 'Expired', 'Trial']],
                    ['label' => 'Activation Date', 'type' => 'date', 'value' => '2026-01-01'],
                    ['label' => 'Expiry Date', 'type' => 'date', 'value' => '2027-01-01'],
                    ['label' => 'System Version', 'type' => 'text', 'value' => '2.7.0'],
                    ['label' => 'Build Number', 'type' => 'text', 'value' => 'B-2026.10.29'],
                    ['label' => 'Database Version', 'type' => 'text', 'value' => 'MySQL 8.0'],
                    ['label' => 'PHP Version', 'type' => 'text', 'value' => '8.2.x'],
                    ['label' => 'Server Information', 'type' => 'text', 'value' => 'Apache / Linux'],
                ],
            ],
        ];

        $this->view('admin/general-settings', [
            'pageTitle' => 'General Settings',
            'subtitle' => 'Configure organization-wide registration, operational and governance preferences.',
            'settingsSections' => $settingsSections,
            'user' => $user ?? ['full_name' => 'Super Administrator'],
            'role' => $roleName,
        ]);
    }

    public function profileOverview(): void
    {
        $sessionUser = $_SESSION['user'] ?? [];
        $userId = (int) ($sessionUser['id'] ?? 0);
        $userModel = new User();
        $profileUser = $userId > 0 ? $userModel->findById($userId) : null;

        if ($profileUser === null) {
            $profileUser = [
                'id' => $userId,
                'first_name' => $sessionUser['full_name'] ?? 'Administrator',
                'last_name' => '',
                'username' => $sessionUser['username'] ?? 'administrator',
                'email' => $sessionUser['email'] ?? 'admin@nrb.gov.mw',
                'phone' => '',
                'employee_number' => '',
                'profile_photo' => '',
                'status' => 'active',
                'failed_attempts' => 0,
                'role_name' => $sessionUser['role'] ?? 'Super Administrator',
                'created_at' => date('Y-m-d H:i:s'),
            ];
        }

        $fullName = trim(((string) ($profileUser['first_name'] ?? '')) . ' ' . ((string) ($profileUser['last_name'] ?? '')));
        $fullName = $fullName !== '' ? $fullName : (string) ($sessionUser['full_name'] ?? 'Administrator');
        $role = trim((string) ($profileUser['role_name'] ?? ($sessionUser['role'] ?? 'Super Administrator')));
        $department = $this->roleDepartment($role);
        $office = $this->latestOffice($userId);
        $lastLogin = $this->latestLoginDate($userId);
        $notifications = $this->userNotifications($userId);
        $timeline = $this->userTimeline($userId);
        $permissions = array_slice(Role::effectivePermissions($role, Role::defaultPermissionsByRole()), 0, 6);
        $servicePeriod = $this->servicePeriodLabel((string) ($profileUser['created_at'] ?? ''));
        $status = strtolower((string) ($profileUser['status'] ?? 'active')) === 'locked' ? 'Locked' : 'Active';
        $riskLevel = (int) ($profileUser['failed_attempts'] ?? 0) > 2 ? 'Medium' : 'Low';
        $employeeId = trim((string) ($profileUser['employee_number'] ?? ''));
        if ($employeeId === '') {
            $employeeId = 'NRB-' . strtoupper(substr(str_replace(' ', '', $role), 0, 3)) . '-' . str_pad((string) ($profileUser['id'] ?? $userId), 4, '0', STR_PAD_LEFT);
        }

        $this->view('admin/profile', [
            'pageTitle' => 'My Profile',
            'subtitle' => 'Review your identity, access rights and operational profile.',
            'user' => [
                'id' => (int) ($profileUser['id'] ?? $userId),
                'full_name' => $fullName,
                'email' => (string) ($profileUser['email'] ?? $sessionUser['email'] ?? 'admin@nrb.gov.mw'),
                'username' => (string) ($profileUser['username'] ?? $sessionUser['username'] ?? 'administrator'),
                'role' => $role,
            ],
            'role' => $role,
            'profile' => [
                'employeeId' => $employeeId,
                'office' => $office,
                'department' => $department,
                'servicePeriod' => $servicePeriod,
                'phone' => (string) ($profileUser['phone'] ?? ''),
                'country' => 'Malawi',
                'lastLogin' => $lastLogin,
                'status' => $status,
                'riskLevel' => $riskLevel,
                'level' => $this->accessLevel($role),
            ],
            'timeline' => $timeline,
            'notifications' => $notifications,
            'documents' => $this->userDocuments((array) $profileUser),
            'permissions' => $permissions,
        ]);
    }

    private function roleDepartment(string $role): string
    {
        $map = [
            'Super Administrator' => 'National Registration and Civic Identity',
            'National Administrator' => 'National Operations Command',
            'District Administrator' => 'District Operations',
            'Registration Officer' => 'Citizen Registration Services',
            'Supervisor' => 'Quality Assurance and Oversight',
            'Card Printing Officer' => 'Card Production Unit',
            'Auditor' => 'Compliance and Audit',
            'Read Only User' => 'Reporting and Monitoring',
        ];

        return $map[$role] ?? 'Administration and Operations';
    }

    private function latestOffice(int $userId): string
    {
        if ($userId <= 0) {
            return 'Headquarters, Lilongwe';
        }

        try {
            $stmt = Database::connection()->prepare(
                'SELECT device, ip_address FROM login_history WHERE user_id = :user_id ORDER BY created_at DESC LIMIT 1'
            );
            $stmt->execute([':user_id' => $userId]);
            $row = $stmt->fetch();
            if ($row !== false) {
                $device = trim((string) ($row['device'] ?? ''));
                if ($device !== '') {
                    return $device;
                }
                $ip = trim((string) ($row['ip_address'] ?? ''));
                if ($ip !== '') {
                    return 'Network access: ' . $ip;
                }
            }
        } catch (\Throwable $e) {
            // fallback below
        }

        return 'Headquarters, Lilongwe';
    }

    private function latestLoginDate(int $userId): string
    {
        if ($userId <= 0) {
            return 'No recent login';
        }

        try {
            $stmt = Database::connection()->prepare(
                'SELECT created_at FROM login_history WHERE user_id = :user_id AND login_status = :status ORDER BY created_at DESC LIMIT 1'
            );
            $stmt->execute([':user_id' => $userId, ':status' => 'success']);
            $row = $stmt->fetch();
            if ($row !== false && !empty($row['created_at'])) {
                return date('d M Y, H:i A', strtotime((string) $row['created_at']));
            }
        } catch (\Throwable $e) {
            // fallback below
        }

        return 'No recent login';
    }

    private function userNotifications(int $userId): array
    {
        if ($userId <= 0) {
            return ['No notifications available'];
        }

        try {
            $stmt = Database::connection()->prepare(
                'SELECT title, message FROM notifications WHERE user_id = :user_id ORDER BY created_at DESC LIMIT 3'
            );
            $stmt->execute([':user_id' => $userId]);
            $rows = $stmt->fetchAll();
            if ($rows !== []) {
                return array_values(array_map(static function (array $row): string {
                    $title = trim((string) ($row['title'] ?? ''));
                    $message = trim((string) ($row['message'] ?? ''));
                    return $title !== '' ? $title : ($message !== '' ? $message : 'System notification');
                }, $rows));
            }
        } catch (\Throwable $e) {
            // fallback below
        }

        return ['Two-factor authentication enabled', 'Quarterly review reminder available', 'New system update published'];
    }

    private function userTimeline(int $userId): array
    {
        $timeline = [];

        if ($userId <= 0) {
            return $timeline;
        }

        try {
            $stmt = Database::connection()->prepare(
                'SELECT action, description, created_at FROM audit_logs WHERE user_id = :user_id ORDER BY created_at DESC LIMIT 4'
            );
            $stmt->execute([':user_id' => $userId]);
            $rows = $stmt->fetchAll();
            foreach ($rows as $row) {
                $title = trim((string) ($row['action'] ?? 'Audit action'));
                $detail = trim((string) ($row['description'] ?? 'System recorded this update'));
                $date = !empty($row['created_at']) ? date('d M', strtotime((string) $row['created_at'])) : 'Today';
                $timeline[] = [
                    'time' => $date,
                    'title' => $title !== '' ? $title : 'Audit event',
                    'detail' => $detail !== '' ? $detail : 'Updated user system activity',
                ];
            }
        } catch (\Throwable $e) {
            // fallback below
        }

        if ($timeline === []) {
            $timeline = [
                ['time' => 'Today', 'title' => 'Profile created', 'detail' => 'Account details were validated on registration'],
                ['time' => 'Recent', 'title' => 'Role assigned', 'detail' => 'Administrative access was granted to this account'],
            ];
        }

        return $timeline;
    }

    private function userDocuments(array $profileUser): array
    {
        $documents = [];
        if (!empty($profileUser['profile_photo'])) {
            $documents[] = [
                'name' => basename((string) $profileUser['profile_photo']),
                'meta' => 'Stored profile photo',
            ];
        }

        if ($documents === []) {
            $documents[] = [
                'name' => 'Profile image',
                'meta' => 'No uploaded image available',
            ];
        }

        return $documents;
    }

    private function accessLevel(string $role): string
    {
        $map = [
            'Super Administrator' => 'Level 9',
            'National Administrator' => 'Level 7',
            'District Administrator' => 'Level 6',
            'Supervisor' => 'Level 5',
            'Registration Officer' => 'Level 4',
            'Card Printing Officer' => 'Level 4',
            'Auditor' => 'Level 5',
            'Read Only User' => 'Level 2',
        ];

        return $map[$role] ?? 'Level 4';
    }

    private function servicePeriodLabel(string $createdAt): string
    {
        if ($createdAt === '') {
            return 'New account';
        }

        try {
            $start = new \DateTimeImmutable($createdAt);
            $now = new \DateTimeImmutable('now');
            $diff = $start->diff($now);
            $years = (int) $diff->format('%y');
            $months = (int) $diff->format('%m');

            $parts = [];
            if ($years > 0) {
                $parts[] = $years . ' year' . ($years > 1 ? 's' : '');
            }
            if ($months > 0 || $parts === []) {
                $parts[] = $months . ' month' . ($months !== 1 ? 's' : '');
            }

            return $parts === [] ? 'New account' : implode(' ', $parts);
        } catch (\Throwable $e) {
            return 'Recently joined';
        }
    }

    public function profilePassword(): void
    {
        $this->renderPage(
            'Change Password',
            'Update password policy and credential reset settings.',
            [
                ['reference' => 'PWD-001', 'name' => 'Password Policy', 'district' => 'HQ', 'status' => 'Current'],
                ['reference' => 'PWD-002', 'name' => 'Last Reset', 'district' => 'HQ', 'status' => '30 Days Ago'],
            ],
            ['Password ID', 'Action', 'Location', 'Status'],
            'Reset Password'
        );
    }

    public function profileActivity(): void
    {
        $this->renderPage(
            'Login Activity',
            'Track recent user sessions and security-sensitive access events.',
            [
                ['reference' => 'ACT-001', 'name' => 'Logged In', 'district' => 'HQ', 'status' => 'Successful'],
                ['reference' => 'ACT-002', 'name' => 'Password Change', 'district' => 'HQ', 'status' => 'Successful'],
            ],
            ['Activity ID', 'Action', 'Location', 'Status'],
            'View History'
        );
    }

    public function registrationCentres(): void
    {
        $this->renderPage(
            'Registration Centres',
            'Inspect district registration centres and service operational capacity.',
            [
                ['reference' => 'RC-001', 'name' => 'Lilongwe District Centre', 'district' => 'Lilongwe', 'status' => 'Operational'],
                ['reference' => 'RC-002', 'name' => 'Blantyre Service Hub', 'district' => 'Blantyre', 'status' => 'Operational'],
            ],
            ['Centre ID', 'Centre Name', 'District', 'Status'],
            'Add Centre'
        );
    }

    public function nationalIdSuspend(): void
    {
        $this->renderPage(
            'National ID Suspension',
            'Review suspended or restricted identity credentials.',
            [
                ['reference' => 'SUS-009', 'name' => 'Ruth Chikuni', 'district' => 'Neno', 'status' => 'Suspended'],
                ['reference' => 'SUS-010', 'name' => 'Stephen Kamangila', 'district' => 'Chiradzulu', 'status' => 'Under Review'],
            ],
            ['Case ID', 'Citizen Name', 'District', 'Status'],
            'Open Suspension Case'
        );
    }

    public function nationalIdHistory(): void
    {
        $this->renderPage(
            'ID History',
            'Trace historical issuance, renewal, replacement and certification events.',
            [
                ['reference' => 'HIS-001', 'name' => 'James Kachali', 'district' => 'Mzimba', 'status' => 'Issued'],
                ['reference' => 'HIS-002', 'name' => 'Marie Mkandawire', 'district' => 'Kasungu', 'status' => 'Renewed'],
            ],
            ['History ID', 'Citizen Name', 'District', 'Status'],
            'Export History'
        );
    }

    public function approvalsRejected(): void
    {
        $this->renderPage(
            'Rejected Records',
            'Review records declined after validation and legal compliance checks.',
            [
                ['reference' => 'REJ-012', 'name' => 'Moses Lungu', 'district' => 'Chikwawa', 'status' => 'Rejected'],
                ['reference' => 'REJ-013', 'name' => 'Annie Msiska', 'district' => 'Phalombe', 'status' => 'Rejected'],
            ],
            ['Record ID', 'Name', 'District', 'Status'],
            'Review Rejects'
        );
    }

    public function approvalsHistory(): void
    {
        $this->renderPage(
            'Approval History',
            'Archive and analyze completed approval cases and decisions.',
            [
                ['reference' => 'AHS-045', 'name' => 'Fumbani Khozomba', 'district' => 'Nkhotakota', 'status' => 'Approved'],
                ['reference' => 'AHS-046', 'name' => 'Winifred Malata', 'district' => 'Karonga', 'status' => 'Rejected'],
            ],
            ['Case ID', 'Name', 'District', 'Status'],
            'Open Archive'
        );
    }

    public function printedCards(): void
    {
        $this->renderPage(
            'Printed Cards',
            'Track cards already issued and dispatched to citizens.',
            [
                ['reference' => 'PC-400', 'name' => 'Lumbani Chisale', 'district' => 'Lilongwe', 'status' => 'Dispatched'],
                ['reference' => 'PC-401', 'name' => 'Tapiwa Mpofu', 'district' => 'Blantyre', 'status' => 'Collected'],
            ],
            ['Card ID', 'Citizen Name', 'District', 'Status'],
            'Search Printed Cards'
        );
    }

    public function reprintCards(): void
    {
        $this->renderPage(
            'Reprint Requests',
            'Manage card reprints following damage, loss or data correction.',
            [
                ['reference' => 'RPT-201', 'name' => 'Joseph Chikuni', 'district' => 'Zomba', 'status' => 'Queued'],
                ['reference' => 'RPT-202', 'name' => 'Maggie Mlotha', 'district' => 'Karonga', 'status' => 'Approved'],
            ],
            ['Request ID', 'Citizen Name', 'District', 'Status'],
            'New Reprint'
        );
    }

    public function printerManagement(): void
    {
        $this->renderPage(
            'Printer Management',
            'Monitor production devices, toner, and print station health.',
            [
                ['reference' => 'PRT-01', 'name' => 'HQ Card Printer 01', 'district' => 'HQ', 'status' => 'Online'],
                ['reference' => 'PRT-02', 'name' => 'District Printer 07', 'district' => 'Blantyre', 'status' => 'Maintenance'],
            ],
            ['Printer ID', 'Printer Name', 'Location', 'Status'],
            'Register Printer'
        );
    }

    public function printingReports(): void
    {
        $this->renderPage(
            'Printing Reports',
            'Generate and review card production performance and issuance trends.',
            [
                ['reference' => 'PRR-110', 'name' => 'Weekly Production', 'district' => 'HQ', 'status' => 'Ready'],
                ['reference' => 'PRR-111', 'name' => 'District Reprints', 'district' => 'HQ', 'status' => 'Ready'],
            ],
            ['Report ID', 'Report Name', 'Scope', 'Status'],
            'Create Report'
        );
    }

    public function reportsCitizens(): void
    {
        $this->renderPage(
            'Citizen Reports',
            'Review demographic, status and district-level population reporting.',
            [
                ['reference' => 'CIT-001', 'name' => 'Adult Registrations', 'district' => 'All', 'status' => 'Updated'],
                ['reference' => 'CIT-002', 'name' => 'Newborn Registrations', 'district' => 'All', 'status' => 'Updated'],
            ],
            ['Report ID', 'Report Name', 'Scope', 'Status'],
            'Generate Report'
        );
    }

    public function reportsBirths(): void
    {
        $this->renderPage(
            'Birth Reports',
            'Analyze birth registration flow, completion rate, and district performance.',
            [
                ['reference' => 'BRR-001', 'name' => 'Monthly Births', 'district' => 'All', 'status' => 'Current'],
                ['reference' => 'BRR-002', 'name' => 'Late Registrations', 'district' => 'All', 'status' => 'Current'],
            ],
            ['Report ID', 'Report Name', 'Scope', 'Status'],
            'Download Report'
        );
    }

    public function reportsDeaths(): void
    {
        $this->renderPage(
            'Death Reports',
            'Track civil registration statistics for death events and documentation.',
            [
                ['reference' => 'DRR-001', 'name' => 'Monthly Deaths', 'district' => 'All', 'status' => 'Current'],
                ['reference' => 'DRR-002', 'name' => 'Pending Certificates', 'district' => 'All', 'status' => 'Current'],
            ],
            ['Report ID', 'Report Name', 'Scope', 'Status'],
            'Download Report'
        );
    }

    public function reportsIds(): void
    {
        $this->renderPage(
            'National ID Reports',
            'Review issuance, expiration and verification trends by district.',
            [
                ['reference' => 'IDR-001', 'name' => 'Issued IDs', 'district' => 'All', 'status' => 'Current'],
                ['reference' => 'IDR-002', 'name' => 'Verification Success', 'district' => 'All', 'status' => 'Current'],
            ],
            ['Report ID', 'Report Name', 'Scope', 'Status'],
            'Generate ID Report'
        );
    }

    public function reportsExport(): void
    {
        $this->renderPage(
            'Data Export',
            'Export registration and identity datasets in standard report formats.',
            [
                ['reference' => 'EXP-001', 'name' => 'Citizen CSV', 'district' => 'All', 'status' => 'Ready'],
                ['reference' => 'EXP-002', 'name' => 'ID XLSX', 'district' => 'All', 'status' => 'Ready'],
            ],
            ['Export ID', 'Dataset', 'Scope', 'Status'],
            'New Export'
        );
    }

    public function reportsCustom(): void
    {
        $this->renderPage(
            'Custom Reports',
            'Build ad-hoc reporting dashboards covering required NRB indicators.',
            [
                ['reference' => 'CR-001', 'name' => 'District Performance', 'district' => 'All', 'status' => 'Saved'],
                ['reference' => 'CR-002', 'name' => 'Pending Queue', 'district' => 'All', 'status' => 'Saved'],
            ],
            ['Report ID', 'Name', 'Scope', 'Status'],
            'Create Custom Report'
        );
    }

    public function securityPermissions(): void
    {
        $this->renderPage(
            'Permissions',
            'Review role permissions and granular access assignments.',
            [
                ['reference' => 'PER-001', 'name' => 'Citizen Registration', 'district' => 'All', 'status' => 'Allowed'],
                ['reference' => 'PER-002', 'name' => 'Card Printing', 'district' => 'All', 'status' => 'Allowed'],
            ],
            ['Permission ID', 'Permission Name', 'Scope', 'Status'],
            'Update Permissions'
        );
    }

    public function securitySessions(): void
    {
        $this->renderPage(
            'Active Sessions',
            'Monitor active staff sessions and remote device access.',
            [
                ['reference' => 'SES-001', 'name' => 'HQ Admin User', 'district' => 'HQ', 'status' => 'Active'],
                ['reference' => 'SES-002', 'name' => 'Blantyre Officer', 'district' => 'Blantyre', 'status' => 'Active'],
            ],
            ['Session ID', 'User', 'Location', 'Status'],
            'Terminate Session'
        );
    }

    public function securityAlerts(): void
    {
        $this->renderPage(
            'Security Alerts',
            'Track suspicious activity, failed login attempts and operational risks.',
            [
                ['reference' => 'ALT-001', 'name' => 'Failed Login Burst', 'district' => 'HQ', 'status' => 'Medium'],
                ['reference' => 'ALT-002', 'name' => 'Privilege Change', 'district' => 'Blantyre', 'status' => 'Low'],
            ],
            ['Alert ID', 'Alert Name', 'Location', 'Status'],
            'Review Alerts'
        );
    }

    public function securitySystemLogs(): void
    {
        $this->renderPage(
            'System Logs',
            'Inspect server and application logs for system health and incidents.',
            [
                ['reference' => 'LOG-001', 'name' => 'API Access', 'district' => 'HQ', 'status' => 'Normal'],
                ['reference' => 'LOG-002', 'name' => 'Database Sync', 'district' => 'HQ', 'status' => 'Normal'],
            ],
            ['Log ID', 'Log Event', 'Location', 'Status'],
            'Download Logs'
        );
    }

    public function settingsSystem(): void
    {
        $this->renderPage(
            'System Configuration',
            'Configure core application services, and environment-level operational settings.',
            [
                ['reference' => 'SYS-001', 'name' => 'Database Pool', 'district' => 'HQ', 'status' => 'Healthy'],
                ['reference' => 'SYS-002', 'name' => 'Session Storage', 'district' => 'HQ', 'status' => 'Healthy'],
            ],
            ['Config ID', 'Setting', 'Scope', 'Status'],
            'Update Configuration'
        );
    }

    public function settingsEmail(): void
    {
        $this->renderPage(
            'Email Settings',
            'Manage mail server credentials, notifications and outgoing correspondence.',
            [
                ['reference' => 'MAIL-001', 'name' => 'SMTP Gateway', 'district' => 'HQ', 'status' => 'Enabled'],
                ['reference' => 'MAIL-002', 'name' => 'Notification Templates', 'district' => 'HQ', 'status' => 'Enabled'],
            ],
            ['Setting ID', 'Service', 'Scope', 'Status'],
            'Edit Mail Settings'
        );
    }

    public function settingsSms(): void
    {
        $this->renderPage(
            'SMS Settings',
            'Configure messaging routes used for verification and reminders.',
            [
                ['reference' => 'SMS-001', 'name' => 'Gateway Endpoint', 'district' => 'HQ', 'status' => 'Enabled'],
                ['reference' => 'SMS-002', 'name' => 'Reminder Queue', 'district' => 'HQ', 'status' => 'Active'],
            ],
            ['Setting ID', 'Service', 'Scope', 'Status'],
            'Edit SMS Settings'
        );
    }

    public function settingsBackups(): void
    {
        $this->renderPage(
            'Backup Management',
            'Review plan for scheduled database and application backups.',
            [
                ['reference' => 'BKP-001', 'name' => 'Daily DB Backup', 'district' => 'HQ', 'status' => 'Completed'],
                ['reference' => 'BKP-002', 'name' => 'Weekly Archive', 'district' => 'HQ', 'status' => 'Scheduled'],
            ],
            ['Backup ID', 'Backup Name', 'Scope', 'Status'],
            'Create Backup'
        );
    }

    public function settingsDatabase(): void
    {
        $this->renderPage(
            'Database Tools',
            'Monitor database health, integrity and maintenance work.',
            [
                ['reference' => 'DB-001', 'name' => 'Integrity Check', 'district' => 'HQ', 'status' => 'Healthy'],
                ['reference' => 'DB-002', 'name' => 'Optimization', 'district' => 'HQ', 'status' => 'Pending'],
            ],
            ['Tool ID', 'Tool Name', 'Scope', 'Status'],
            'Run Database Check'
        );
    }

    public function settingsMaintenance(): void
    {
        $this->renderPage(
            'Maintenance Mode',
            'Schedule maintenance windows and control service availability.',
            [
                ['reference' => 'MT-001', 'name' => 'Planned Upgrade', 'district' => 'HQ', 'status' => 'Scheduled'],
                ['reference' => 'MT-002', 'name' => 'Emergency Mode', 'district' => 'HQ', 'status' => 'Disabled'],
            ],
            ['Maintenance ID', 'Window', 'Scope', 'Status'],
            'Create Maintenance Window'
        );
    }

    private function renderPage(string $title, string $subtitle, array $rows, array $columns, string $actionLabel): void
    {
        $stats = [
            ['label' => 'Active', 'value' => '1,288', 'icon' => 'fa-user-check', 'color' => 'green'],
            ['label' => 'Pending', 'value' => '146', 'icon' => 'fa-clock', 'color' => 'amber'],
            ['label' => 'Approved', 'value' => '965', 'icon' => 'fa-circle-check', 'color' => 'blue'],
        ];

        $this->view('workflow/operations', [
            'pageTitle' => $title,
            'subtitle' => $subtitle,
            'user' => $_SESSION['user'] ?? ['full_name' => 'Admin User'],
            'role' => ($_SESSION['user']['role'] ?? 'Administrator'),
            'stats' => $stats,
            'columns' => $columns,
            'rows' => $rows,
            'actionLabel' => $actionLabel,
        ]);
    }
}
