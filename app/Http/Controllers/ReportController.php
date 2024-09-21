<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Claim;

class ReportController extends Controller
{
    public function generateReport(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $claims = Claim::with(['lecturer', 'details', 'additionalExpenses'])
            ->whereBetween('submission_date', [$startDate, $endDate])
            ->get();

        $totalAmount = $claims->sum('total_amount');
        $claimCount = $claims->count();

        $report = [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'total_amount' => $totalAmount,
            'claim_count' => $claimCount,
            'claims' => $claims,
        ];

        return response()->json($report);
    }
}
