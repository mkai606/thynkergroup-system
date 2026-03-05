<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\User;

class LeaderboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $topAgents = User::where('role', 'agent')
            ->where('status', 'active')
            ->orderByDesc('monthly_exp')
            ->limit(50)
            ->get();

        return view('agent.leaderboard', compact('user', 'topAgents'));
    }
}
