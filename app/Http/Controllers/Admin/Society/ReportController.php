<?php

namespace App\Http\Controllers\Admin\Society;

use App\Exports\ParticipationExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function download(string $id)
    {
        return Excel::download(new ParticipationExport($id), 'participants.xlsx');
    }
}
