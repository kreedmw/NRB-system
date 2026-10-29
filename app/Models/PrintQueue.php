<?php

declare(strict_types=1);

namespace App\Models;

class PrintQueue
{
    public static function statusOptions(): array
    {
        return [
            'Pending',
            'Approved',
            'Queued',
            'Printing',
            'Printed',
            'Quality Check',
            'Rejected',
            'Dispatched',
            'Collected',
        ];
    }

    public static function stats(): array
    {
        return [
            'pending' => 1250,
            'printed_today' => 430,
            'failed' => 12,
            'quality_check' => 85,
            'dispatched' => 4520,
            'cards_waiting' => 1245,
        ];
    }

    public static function queueItems(): array
    {
        return [
            [
                'queue_no' => 'Q00001',
                'photo' => 'JB',
                'full_name' => 'John Banda',
                'national_id' => '12345678',
                'application_no' => 'APP-2026-1045',
                'registration_ref' => 'REG-1045',
                'gender' => 'Male',
                'dob' => '12/04/1995',
                'district' => 'Lilongwe',
                'registration_centre' => 'Lilongwe Central',
                'collection_centre' => 'Lilongwe Central',
                'date_approved' => '29 Oct 2026',
                'status' => 'Pending',
                'priority' => 'Normal',
            ],
            [
                'queue_no' => 'Q00002',
                'photo' => 'MP',
                'full_name' => 'Mary Phiri',
                'national_id' => '87654321',
                'application_no' => 'APP-2026-1046',
                'registration_ref' => 'REG-1046',
                'gender' => 'Female',
                'dob' => '07/11/1998',
                'district' => 'Blantyre',
                'registration_centre' => 'Blantyre City',
                'collection_centre' => 'Blantyre City',
                'date_approved' => '29 Oct 2026',
                'status' => 'Printing',
                'priority' => 'Urgent',
            ],
            [
                'queue_no' => 'Q00003',
                'photo' => 'PZ',
                'full_name' => 'Peter Zulu',
                'national_id' => '45678912',
                'application_no' => 'APP-2026-1047',
                'registration_ref' => 'REG-1047',
                'gender' => 'Male',
                'dob' => '18/02/2001',
                'district' => 'Mzimba',
                'registration_centre' => 'Mzimba North',
                'collection_centre' => 'Mzimba North',
                'date_approved' => '28 Oct 2026',
                'status' => 'Quality Check',
                'priority' => 'Normal',
            ],
            [
                'queue_no' => 'Q00004',
                'photo' => 'AC',
                'full_name' => 'Aisha Chikopa',
                'national_id' => '98765432',
                'application_no' => 'APP-2026-1048',
                'registration_ref' => 'REG-1048',
                'gender' => 'Female',
                'dob' => '09/09/1989',
                'district' => 'Kasungu',
                'registration_centre' => 'Kasungu Office',
                'collection_centre' => 'Kasungu Office',
                'date_approved' => '29 Oct 2026',
                'status' => 'Approved',
                'priority' => 'High',
            ],
            [
                'queue_no' => 'Q00005',
                'photo' => 'LM',
                'full_name' => 'Lumbani Moyo',
                'national_id' => '65432109',
                'application_no' => 'APP-2026-1049',
                'registration_ref' => 'REG-1049',
                'gender' => 'Male',
                'dob' => '23/06/1992',
                'district' => 'Zomba',
                'registration_centre' => 'Zomba Central',
                'collection_centre' => 'Zomba Central',
                'date_approved' => '27 Oct 2026',
                'status' => 'Dispatched',
                'priority' => 'Normal',
            ],
        ];
    }

    public static function printerInfo(): array
    {
        return [
            'name' => 'HID Fargo HDP5000',
            'status' => 'ONLINE',
            'ribbon' => '75%',
            'cards_remaining' => 3500,
            'last_maintenance' => '20/10/2026',
            'location' => 'Lilongwe Central',
            'batch_id' => 'PRINT-20261029-001',
        ];
    }

    public static function printHistory(): array
    {
        return [
            [
                'card_id' => '12345678',
                'citizen' => 'John Banda',
                'printed_by' => 'John Phiri',
                'printer' => 'Printer 1',
                'date' => '29 Oct 2026',
                'time' => '14:35',
                'result' => 'Successful',
            ],
            [
                'card_id' => '87654321',
                'citizen' => 'Mary Phiri',
                'printed_by' => 'Grace Nkhoma',
                'printer' => 'Printer 2',
                'date' => '29 Oct 2026',
                'time' => '13:20',
                'result' => 'Successful',
            ],
            [
                'card_id' => '45678912',
                'citizen' => 'Peter Zulu',
                'printed_by' => 'A. Kachipapa',
                'printer' => 'Printer 1',
                'date' => '28 Oct 2026',
                'time' => '16:45',
                'result' => 'Needs Reprint',
            ],
        ];
    }

    public static function auditTrail(): array
    {
        return [
            ['time' => '29 Oct 2026 14:35', 'actor' => 'Admin John Phiri', 'action' => 'Printed ID: 12345678'],
            ['time' => '29 Oct 2026 14:40', 'actor' => 'QA Officer Mary Banda', 'action' => 'Approved Card'],
            ['time' => '29 Oct 2026 15:02', 'actor' => 'Supervisor Asime Kulemeka', 'action' => 'Dispatched to Centre'],
        ];
    }

    public static function batches(): array
    {
        return [
            ['id' => 'PRINT-20261029-001', 'cards' => 500, 'printer' => 'HID Fargo Printer 1', 'created_by' => 'Admin', 'status' => 'In Progress'],
            ['id' => 'PRINT-20261029-002', 'cards' => 200, 'printer' => 'HID Fargo Printer 2', 'created_by' => 'Admin', 'status' => 'Queued'],
        ];
    }
}
