<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BrandLead;
use Illuminate\Http\Request;

class BrandLeadController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'brand_name' => ['required', 'string', 'max:255'],
            'product_category' => ['nullable', 'string', 'max:255'],
            'campaign_goal' => ['nullable', 'string'],
            'budget_range' => ['nullable', 'string', 'max:100'],
        ]);

        $existing = BrandLead::where('email', $validated['email'])
            ->whereIn('status', ['new', 'contacted'])
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'You already have a pending inquiry. We will reach out soon!',
            ], 422);
        }

        BrandLead::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Thank you! Our team will contact you within 24 hours to discuss your campaign.',
        ]);
    }
}
