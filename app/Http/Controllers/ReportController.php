<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Contract;
use Illuminate\Http\Request;
use App\Exports\ContractsExport;
use Illuminate\Support\Facades\Redirect;
use Maatwebsite\Excel\Facades\Excel as Excel;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('Reports/Index', ['year' => (int)date('Y'), 'years' => array_reverse(range((int)date('Y') - 20, (int)date('Y')))]);
    }

    public function print(Request $request)
    {
        $year = (int)$request->get('year');
        return Excel::download(new ContractsExport($year), 'Jahresbericht-' . $year .'.xlsx');
    }
}
