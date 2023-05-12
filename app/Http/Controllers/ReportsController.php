<?php

namespace App\Http\Controllers;

use App\Exports\ReportExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{

    public function get_report($type, $export, Request $request)
    {
        $reportClass = 'App\Reports\\' . $type;
        $report = (new $reportClass())->render();

        if ($export == 1) {
            return Excel::download(new ReportExport($report), $type . '.xlsx');
        }

        return view('newDashboard.reports.report', compact('report'));
    }
}
