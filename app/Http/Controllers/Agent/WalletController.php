<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\PayoutRequest;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $wallet = $user->wallet;

        // Transaction filter
        $filter = $request->input('filter');
        $txQuery = $wallet ? $wallet->transactions()->latest() : null;

        if ($txQuery && $filter && in_array($filter, ['credit', 'debit'])) {
            $txQuery->where('type', $filter);
        }

        $transactions = $txQuery ? $txQuery->limit(30)->get() : collect();

        // Payout requests for this user
        $payouts = PayoutRequest::where('user_id', $user->id)
            ->latest()
            ->limit(20)
            ->get();

        $pendingPayouts = $payouts->whereIn('status', ['requested', 'processing']);
        $totalPaid = $payouts->where('status', 'completed')->sum('amount');
        $totalPending = $pendingPayouts->sum('amount');

        return view('agent.wallet', compact(
            'user', 'transactions', 'filter',
            'payouts', 'pendingPayouts', 'totalPaid', 'totalPending'
        ));
    }
}
