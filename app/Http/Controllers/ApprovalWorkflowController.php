<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApprovalWorkflowController extends Controller
{
    public function getPendingApprovals()
    {
        $userRole = auth()->user()->role;
        $pendingApprovals = ApprovalWorkflow::with(['claim', 'claim.lecturer'])
            ->where('stage', $userRole)
            ->where('status', 'pending')
            ->get();

        return response()->json($pendingApprovals);
    }

    public function approve(Request $request, $id)
    {
        $workflow = ApprovalWorkflow::findOrFail($id);
        
        if ($workflow->stage !== auth()->user()->role) {
            return response()->json(['message' => 'Unauthorized to approve this claim'], 403);
        }

        DB::beginTransaction();

        try {
            $workflow->update([
                'status' => 'approved',
                'approver_id' => auth()->id(),
                'comments' => $request->input('comments'),
                'approved_at' => now(),
            ]);

            $claim = $workflow->claim;
            $nextStage = $this->getNextStage($workflow->stage);

            if ($nextStage) {
                ApprovalWorkflow::create([
                    'claim_id' => $claim->id,
                    'stage' => $nextStage,
                    'status' => 'pending',
                ]);
            } else {
                $claim->update(['status' => 'approved']);
            }

            DB::commit();
            return response()->json(['message' => 'Claim approved successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error approving claim'], 500);
        }
    }

    public function reject(Request $request, $id)
    {
        $workflow = ApprovalWorkflow::findOrFail($id);
        
        if ($workflow->stage !== auth()->user()->role) {
            return response()->json(['message' => 'Unauthorized to reject this claim'], 403);
        }

        DB::beginTransaction();

        try {
            $workflow->update([
                'status' => 'rejected',
                'approver_id' => auth()->id(),
                'comments' => $request->input('comments'),
                'approved_at' => now(),
            ]);

            $claim = $workflow->claim;
            $claim->update(['status' => 'rejected']);

            DB::commit();
            return response()->json(['message' => 'Claim rejected successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error rejecting claim'], 500);
        }
    }

    private function getNextStage($currentStage)
    {
        $stages = ['hod', 'dean', 'provost', 'auditor', 'vc'];
        $currentIndex = array_search($currentStage, $stages);
        return isset($stages[$currentIndex + 1]) ? $stages[$currentIndex + 1] : null;
    }
}
