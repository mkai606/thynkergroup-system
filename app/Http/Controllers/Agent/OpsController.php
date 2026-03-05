<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;

class OpsController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Active ops: accepted or submitted applications
        $activeApps = $user->taskApplications()
            ->with('task.campaign')
            ->whereIn('status', ['accepted', 'submitted', 'applied'])
            ->latest('applied_at')
            ->get();

        // Mission log: completed/settled/rejected
        $historyApps = $user->taskApplications()
            ->with('task.campaign')
            ->whereIn('status', ['approved', 'paid', 'rejected'])
            ->latest('applied_at')
            ->get();

        return view('agent.ops', compact('user', 'activeApps', 'historyApps'));
    }
}
