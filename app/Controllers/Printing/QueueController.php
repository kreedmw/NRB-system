<?php

declare(strict_types=1);

namespace App\Controllers\Printing;

use App\Core\Controller;
use App\Models\PrintQueue;
use App\Models\Role;

class QueueController extends Controller
{
    public function index(): void
    {
        $user = $_SESSION['user'] ?? [];
        $role = trim((string) ($user['role'] ?? 'Card Printing Officer'));
        $permissions = Role::effectivePermissions($role, Role::defaultPermissionsByRole());

        if (!in_array('view_print_queue', $permissions, true) && !in_array('print_card', $permissions, true)) {
            $dashboard = '/login';
            if ($role !== '') {
                $dashboard = '/' . strtolower(str_replace(' ', '-', $role));
            }
            $this->redirect($dashboard . '/dashboard');
        }

        $this->view('printing/queue', [
            'pageTitle' => 'National ID Card Printing Queue',
            'subtitle' => 'Manage citizens whose National ID cards are approved and ready for production.',
            'user' => [
                'full_name' => $user['full_name'] ?? 'Card Printing Officer',
                'role' => $role,
                'username' => $user['username'] ?? 'printing.officer',
                'email' => $user['email'] ?? 'printing@nrb.gov.mw',
            ],
            'role' => $role,
            'printingCentre' => 'Lilongwe Central',
            'printer' => 'HID Fargo HDP5000',
            'printerStatus' => 'Online',
            'stats' => PrintQueue::stats(),
            'statusOptions' => PrintQueue::statusOptions(),
            'queueItems' => PrintQueue::queueItems(),
            'printerInfo' => PrintQueue::printerInfo(),
            'history' => PrintQueue::printHistory(),
            'auditTrail' => PrintQueue::auditTrail(),
            'batches' => PrintQueue::batches(),
            'today' => date('d M Y'),
        ]);
    }
}
