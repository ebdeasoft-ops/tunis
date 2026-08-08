<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AttendancesTemplateExport implements FromArray, WithHeadings
{
    public function headings(): array
    {
        return ['employee_id', 'date', 'check_in', 'check_out', 'status'];
    }

    public function array(): array
    {
        return [
            [1, date('Y-m-d'), '08:00:00', '16:00:00', 'present']
        ];
    }
}