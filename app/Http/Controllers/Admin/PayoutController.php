<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PayoutRequest;
use App\Models\PayoutTransaction;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;

class PayoutController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->input('status', 'requested');

        $query = PayoutRequest::with('user')
            ->latest();

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $payouts = $query->get();

        $counts = [
            'requested' => PayoutRequest::where('status', 'requested')->count(),
            'processing' => PayoutRequest::where('status', 'processing')->count(),
            'completed' => PayoutRequest::where('status', 'completed')->count(),
            'rejected' => PayoutRequest::where('status', 'rejected')->count(),
        ];

        return view('admin.payouts.index', compact('payouts', 'status', 'counts'));
    }

    public function show(PayoutRequest $payout)
    {
        $payout->load('user', 'transaction');

        return view('admin.payouts.show', compact('payout'));
    }

    public function process(PayoutRequest $payout)
    {
        $payout->update(['status' => 'processing']);

        return back()->with('success', 'Payout marked as processing.');
    }

    public function complete(Request $request, PayoutRequest $payout)
    {
        $payout->load('user');

        // 1. Mark payout completed
        $payout->update(['status' => 'completed']);

        // 2. Log payout transaction
        PayoutTransaction::create([
            'payout_request_id' => $payout->id,
            'provider_ref' => $request->input('provider_ref', 'TNG-' . now()->format('YmdHis')),
            'amount' => $payout->amount,
            'status' => 'success',
            'processed_at' => now(),
        ]);

        // 3. Log wallet transaction (credit + debit for record)
        $wallet = $payout->user->wallet;
        if ($wallet) {
            WalletTransaction::create([
                'wallet_id' => $wallet->id,
                'type' => 'credit',
                'reason' => 'Campaign payout — approved',
                'amount' => $payout->amount,
                'reference_type' => 'App\\Models\\PayoutRequest',
                'reference_id' => $payout->id,
            ]);
            WalletTransaction::create([
                'wallet_id' => $wallet->id,
                'type' => 'debit',
                'reason' => 'TNG transfer — completed',
                'amount' => $payout->amount,
                'reference_type' => 'App\\Models\\PayoutRequest',
                'reference_id' => $payout->id,
            ]);
        }

        // 4. Update application status to paid
        $application = $payout->approval?->submission?->application;
        if ($application) {
            $application->update(['status' => 'paid']);
        }

        return redirect()->route('admin.payouts')
            ->with('success', 'Payout RM' . number_format($payout->amount, 2) . ' completed for ' . $payout->user->name);
    }

    public function reject(PayoutRequest $payout)
    {
        $payout->load('user');

        $payout->update(['status' => 'rejected']);

        return redirect()->route('admin.payouts')
            ->with('success', 'Payout rejected for ' . $payout->user->name);
    }
}
