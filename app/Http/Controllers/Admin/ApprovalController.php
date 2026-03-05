<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PayoutRequest;
use App\Models\PayoutTransaction;
use App\Models\TaskApproval;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;

class ApprovalController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->input('status', 'pending');

        $query = TaskApproval::with([
            'submission.application.user',
            'submission.application.task.campaign',
        ]);

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        // Filter by task
        if ($request->filled('task') && $request->task !== 'all') {
            $query->whereHas('submission.application.task', function ($q) use ($request) {
                $q->where('id', $request->task);
            });
        }

        // Filter by fraud risk
        if ($request->filled('fraud') && $request->fraud !== 'all') {
            $query->where('fraud_risk', 'like', '%' . $request->fraud . '%');
        }

        $approvals = $query->latest()->get();

        $counts = [
            'pending' => TaskApproval::where('status', 'pending')->count(),
            'approved' => TaskApproval::where('status', 'approved')->count(),
            'paid' => TaskApproval::where('status', 'paid')->count(),
            'rejected' => TaskApproval::where('status', 'rejected')->count(),
        ];

        // Get distinct tasks for filter dropdown
        $tasks = \App\Models\Task::whereHas('applications.submissions.approvals')->get();

        return view('admin.approvals.index', compact('approvals', 'tasks', 'status', 'counts'));
    }

    public function show(TaskApproval $approval)
    {
        $approval->load([
            'submission.application.user',
            'submission.application.task.campaign',
            'submission.fraudEvents',
        ]);

        $pendingCount = TaskApproval::where('status', 'pending')->count();
        $awaitingPayment = TaskApproval::where('status', 'approved')->count();

        return view('admin.approvals.show', compact('approval', 'pendingCount', 'awaitingPayment'));
    }

    public function approve(Request $request, TaskApproval $approval)
    {
        $approval->load('submission.application.task');

        $application = $approval->submission->application;
        $task = $application->task;
        $user = $application->user;

        // 1. Update approval status to approved (awaiting payment)
        $approval->update([
            'status' => 'approved',
            'reviewed_at' => now(),
            'reviewer_id' => auth()->id(),
        ]);

        // 2. Update application status
        $application->update(['status' => 'approved']);

        // 3. Award EXP
        $expAwarded = $approval->exp_awarded;
        if ($expAwarded > 0) {
            $user->increment('total_exp', $expAwarded);
            $user->increment('monthly_exp', $expAwarded);
        }

        // 4. Update campaign spent budget
        $task->campaign->increment('spent_budget', $task->reward_amount);

        // 5. Increment completed tasks
        $user->increment('completed_tasks');

        // Redirect back to same page to show QR + Mark Paid
        return redirect()->route('admin.approvals.show', $approval)
            ->with('success', 'Approved! ' . $expAwarded . ' EXP awarded. Now transfer RM' . number_format($task->reward_amount, 2) . ' to agent.');
    }

    public function markPaid(Request $request, TaskApproval $approval)
    {
        $approval->load('submission.application.task');

        $application = $approval->submission->application;
        $task = $application->task;
        $user = $application->user;

        // 1. Update approval status to paid
        $approval->update(['status' => 'paid']);

        // 2. Update application status
        $application->update(['status' => 'paid']);

        // 3. Create payout request + transaction record
        $payout = PayoutRequest::create([
            'user_id' => $user->id,
            'approval_id' => $approval->id,
            'amount' => $task->reward_amount,
            'provider' => 'TNG',
            'status' => 'completed',
        ]);

        PayoutTransaction::create([
            'payout_request_id' => $payout->id,
            'provider_ref' => 'TNG-' . now()->format('YmdHis'),
            'amount' => $task->reward_amount,
            'status' => 'success',
            'processed_at' => now(),
        ]);

        // 4. Log wallet transactions
        $wallet = $user->wallet;
        if ($wallet) {
            WalletTransaction::create([
                'wallet_id' => $wallet->id,
                'type' => 'credit',
                'reason' => 'Campaign payout — ' . $task->title,
                'amount' => $task->reward_amount,
                'reference_type' => 'App\\Models\\TaskApproval',
                'reference_id' => $approval->id,
            ]);
            WalletTransaction::create([
                'wallet_id' => $wallet->id,
                'type' => 'debit',
                'reason' => 'TNG transfer — paid',
                'amount' => $task->reward_amount,
                'reference_type' => 'App\\Models\\PayoutRequest',
                'reference_id' => $payout->id,
            ]);
        }

        return redirect()->route('admin.approvals')
            ->with('success', 'RM' . number_format($task->reward_amount, 2) . ' paid to ' . $user->name . ' via TNG.');
    }

    public function reject(Request $request, TaskApproval $approval)
    {
        $approval->update([
            'status' => 'rejected',
            'reviewed_at' => now(),
            'reviewer_id' => auth()->id(),
        ]);

        return redirect()->route('admin.approvals')
            ->with('success', 'Submission rejected.');
    }
}
