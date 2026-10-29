<?php
declare(strict_types=1);

namespace App\Controllers\Citizen;

use App\Core\Controller;

class BirthController extends Controller
{
    public function index(): void
    {
        $rows = [
            ['reference' => 'BR-2026-001', 'name' => 'Amina Moyo', 'district' => 'Lilongwe', 'date' => '2026-10-27', 'status' => 'Verified'],
            ['reference' => 'BR-2026-002', 'name' => 'Joseph Banda', 'district' => 'Blantyre', 'date' => '2026-10-25', 'status' => 'Pending'],
            ['reference' => 'BR-2026-003', 'name' => 'Grace Phiri', 'district' => 'Mzimba', 'date' => '2026-10-23', 'status' => 'Approved'],
        ];

        $this->view('workflow/operations', [
            'pageTitle' => 'Birth Registration',
            'subtitle' => 'Capture, review, and verify birth records across districts.',
            'user' => $_SESSION['user'] ?? ['full_name' => 'Admin User'],
            'role' => ($_SESSION['user']['role'] ?? 'Administrator'),
            'stats' => [
                ['label' => 'New Births', 'value' => '2,340', 'icon' => 'fa-baby', 'color' => 'green'],
                ['label' => 'Awaiting Review', 'value' => '124', 'icon' => 'fa-clock', 'color' => 'amber'],
                ['label' => 'Verified Today', 'value' => '310', 'icon' => 'fa-circle-check', 'color' => 'blue'],
            ],
            'columns' => ['Reference', 'Child Name', 'District', 'Date Recorded', 'Status'],
            'rows' => $rows,
            'actionLabel' => 'New Birth Record',
        ]);
    }
}
