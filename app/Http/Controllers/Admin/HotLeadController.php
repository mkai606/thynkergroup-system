<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BrandLead;
use Illuminate\Http\Request;

class HotLeadController extends Controller
{
    public function index(Request $request)
    {
        $query = BrandLead::query();

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('brand_name', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $leads = $query->latest()->get();

        return view('admin.hot-leads.index', compact('leads'));
    }

    public function updateStatus(Request $request, BrandLead $lead)
    {
        $request->validate([
            'status' => ['required', 'in:new,contacted,qualified,converted,archived'],
            'admin_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $lead->update([
            'status' => $request->status,
            'admin_notes' => $request->admin_notes ?? $lead->admin_notes,
        ]);

        return back()->with('success', "{$lead->brand_name} status updated to {$request->status}.");
    }
}
