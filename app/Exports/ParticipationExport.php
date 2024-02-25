<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ParticipationExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{

    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $data = DB::table('competition_user')
            ->where('competition_id', $this->id)
            ->join('users', 'users.id', '=', 'competition_user.user_id')
            ->select('users.name', 'users.college', 'users.email', 'users.phone_number', 'competition_user.team_code', 'competition_user.team_size', 'competition_user.leader')
            ->get();
        return $data;
    }

    public function headings(): array
    {
        return ["Name", "College", "Email", "Phone Number", "Team Code", "Team Size", "Leader"];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]]
        ];
    }
}
