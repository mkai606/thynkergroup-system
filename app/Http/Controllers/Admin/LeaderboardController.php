<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class LeaderboardController extends Controller
{
    public function index()
    {
        $sidekicks = User::where('role', 'agent')
            ->where('status', 'active')
            ->orderByDesc('monthly_exp')
            ->limit(50)
            ->get();

        return view('admin.leaderboard.index', compact('sidekicks'));
    }
}
