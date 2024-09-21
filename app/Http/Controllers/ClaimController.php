<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use App\Models\ClaimDetail;
use App\Models\AdditionalExpense;
use App\Models\ApprovalWorkflow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClaimController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'submission_date' => 'required|date',
            'details' => 'required|array',
            'details.*.lecture_date' => 'required|date',
            'details.*.programme' => 'required',
            'details.*.course' => 'required',
            'details.*.start_time' => 'required',
            'details.*.end_time' => 'required',
            'details.*.duration' => 'required|integer',
            'details.*.rate' => 'required|numeric',
            'additional_expenses' => 'array',
        ]);

        DB::beginTransaction();

        try {
            $claim = Claim::create([
                'lecturer_id' => auth()->id(),
                'submission_date' => $validated['submission_date'],
                'total_amount' => 0,
                'status' => 'pending',
            ]);

            $total = 0;

            foreach ($validated['details'] as $detail) {
                $subtotal = $detail['duration'] * $detail['rate'];
                $total += $subtotal;

                ClaimDetail::create([
                    'claim_id' => $claim->id,
                    'lecture_date' => $detail['lecture_date'],
                    'programme' => $detail['programme'],
                    'course' => $detail['course'],
                    'start_time' => $detail['start_time'],
                    'end_time' => $detail['end_time'],
                    'duration' => $detail['duration'],
                    'rate' => $detail['rate'],
                    'subtotal' => $subtotal,
                ]);
            }

            if (isset($validated['additional_expenses'])) {
                foreach ($validated['additional_expenses'] as $expense) {
                    $total += $expense['amount'];
                    AdditionalExpense::create([
                        'claim_id' => $claim->id,
                        'description' => $expense['description'],
                        'amount' => $expense['amount'],
                    ]);
                }
            }

            $claim->update(['total_amount' => $total]);

            $this->createApprovalWorkflow($claim->id);

            DB::commit();

            return response()->json(['message' => 'Claim submitted successfully', 'claim' => $claim]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error submitting claim'], 500);
        }
    }

    private function createApprovalWorkflow($claimId)
    {
        $stages = ['hod', 'dean', 'provost', 'auditor', 'vc'];

        foreach ($stages as $stage) {
            ApprovalWorkflow::create([
                'claim_id' => $claimId,
                'stage' => $stage,
                'status' => 'pending',
            ]);
        }
    }

    public function index()
    {
        $claims = Claim::with('details', 'additionalExpenses', 'approvalWorkflows')
            ->where('lecturer_id', auth()->id())
            ->get();

        return response()->json($claims);
    }

    public function show($id)
    {
        $claim = Claim::with('details', 'additionalExpenses', 'approvalWorkflows')
            ->findOrFail($id);

        return response()->json($claim);
    }

    public function update(Request $request, $id)
    {
        $claim = Claim::findOrFail($id);

        if ($claim->status !== 'pending') {
            return response()->json(['message' => 'Cannot update a processed claim'], 403);
        }

        // Validation and update logic here (similar to store method)

        return response()->json(['message' => 'Claim updated successfully', 'claim' => $claim]);
    }

    public function destroy($id)
    {
        $claim = Claim::findOrFail($id);

        if ($claim->status !== 'pending') {
            return response()->json(['message' => 'Cannot delete a processed claim'], 403);
        }

        $claim->delete();

        return response()->json(['message' => 'Claim deleted successfully']);
    }
}