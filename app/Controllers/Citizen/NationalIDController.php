<?php
declare(strict_types=1);

namespace App\Controllers\Citizen;

use App\Core\Controller;

class NationalIDController extends Controller
{
    public function issue(): void
    {
        $this->renderPage('National ID Issuance', 'Issue new national identification cards for eligible citizens.', [
            ['reference' => 'ID-2026-110', 'name' => 'Moses Nkhoma', 'district' => 'Lilongwe', 'status' => 'Queued'],
            ['reference' => 'ID-2026-111', 'name' => 'Tiwonge Mkandawire', 'district' => 'Zomba', 'status' => 'In Progress'],
            ['reference' => 'ID-2026-112', 'name' => 'Mary Banda', 'district' => 'Kasungu', 'status' => 'Ready'],
        ]);
    }

    public function search(): void
    {
        $this->renderPage('National ID Search', 'Search citizen identity records and verification history.', [
            ['reference' => 'ID-2026-076', 'name' => 'Lydia Kachipula', 'district' => 'Blantyre', 'status' => 'Verified'],
            ['reference' => 'ID-2026-077', 'name' => 'Charles Kamanga', 'district' => 'Kasungu', 'status' => 'Active'],
        ]);
    }

    public function verify(): void
    {
        $this->renderPage('ID Verification', 'Check the validity of issued cards against national records.', [
            ['reference' => 'ID-2026-032', 'name' => 'Grace Kapito', 'district' => 'Mzimba', 'status' => 'Verified'],
            ['reference' => 'ID-2026-033', 'name' => 'Samuel Mvula', 'district' => 'Mangochi', 'status' => 'Flagged'],
        ]);
    }

    public function renew(): void
    {
        $this->renderPage('ID Renewal', 'Manage renewals, expiry notices, and revalidation requests.', [
            ['reference' => 'ID-2026-011', 'name' => 'Alice Jere', 'district' => 'Lilongwe', 'status' => 'Pending'],
            ['reference' => 'ID-2026-012', 'name' => 'Daniel Chavula', 'district' => 'Blantyre', 'status' => 'Approved'],
        ]);
    }

    public function replace(): void
    {
        $this->renderPage('Lost ID Replacement', 'Process replacement requests for damaged, stolen, or lost national IDs.', [
            ['reference' => 'ID-2026-220', 'name' => 'Patricia Banda', 'district' => 'Zomba', 'status' => 'Awaiting Review'],
            ['reference' => 'ID-2026-221', 'name' => 'Emmanuel Nyirenda', 'district' => 'Kasungu', 'status' => 'Approved'],
        ]);
    }

    private function renderPage(string $title, string $subtitle, array $rows): void
    {
        $this->view('workflow/operations', [
            'pageTitle' => $title,
            'subtitle' => $subtitle,
            'user' => $_SESSION['user'] ?? ['full_name' => 'Admin User'],
            'role' => ($_SESSION['user']['role'] ?? 'Administrator'),
            'stats' => [
                ['label' => 'Records', 'value' => '4,960', 'icon' => 'fa-id-card', 'color' => 'blue'],
                ['label' => 'Pending', 'value' => '182', 'icon' => 'fa-clock', 'color' => 'amber'],
                ['label' => 'Verified', 'value' => '4,778', 'icon' => 'fa-circle-check', 'color' => 'green'],
            ],
            'columns' => ['ID Reference', 'Citizen Name', 'District', 'Status'],
            'rows' => $rows,
            'actionLabel' => $title === 'National ID Issuance' ? 'Issue New ID' : 'New ID Action',
            'issueMode' => $title === 'National ID Issuance',
        ]);
    }
}
