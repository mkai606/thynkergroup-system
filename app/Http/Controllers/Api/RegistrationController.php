<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use App\Models\RegistrationSocialProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RegistrationController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'email' => ['required', 'email', 'max:255'],
            'followers' => ['required', 'integer', 'min:0'],
            'tiktok' => ['nullable', 'string', 'max:100'],
            'instagram' => ['nullable', 'string', 'max:100'],
            'niche' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'avg_views' => ['nullable', 'integer', 'min:0'],
            'content_types' => ['nullable', 'array'],
            'agree' => ['required', 'accepted'],
        ]);

        // Check duplicate email
        $existing = Registration::where('email', $validated['email'])
            ->whereIn('status', ['pending', 'approved'])
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'Email already registered. Please wait for review.',
            ], 422);
        }

        // Build notes from extra fields
        $notes = collect();
        if (!empty($validated['niche'])) $notes->push('Niche: ' . $validated['niche']);
        if (!empty($validated['location'])) $notes->push('Location: ' . $validated['location']);
        if (!empty($validated['avg_views'])) $notes->push('Avg Views: ' . $validated['avg_views']);
        if (!empty($validated['content_types'])) $notes->push('Content: ' . implode(', ', $validated['content_types']));

        $registration = Registration::create([
            'full_name' => $validated['full_name'],
            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'status' => 'pending',
            'verification_status' => 'unverified',
            'submitted_at' => now(),
            'notes' => $notes->isNotEmpty() ? $notes->implode(' | ') : null,
        ]);

        // Create social profiles
        $followers = (int) $validated['followers'];

        if (!empty($validated['tiktok'])) {
            $registration->socialProfiles()->create([
                'platform' => 'tiktok',
                'handle' => $validated['tiktok'],
                'followers' => $followers,
            ]);
        }

        if (!empty($validated['instagram'])) {
            $registration->socialProfiles()->create([
                'platform' => 'instagram',
                'handle' => $validated['instagram'],
                'followers' => $followers,
            ]);
        }

        // If neither provided, create generic entry
        if (empty($validated['tiktok']) && empty($validated['instagram'])) {
            $registration->socialProfiles()->create([
                'platform' => 'other',
                'handle' => $validated['full_name'],
                'followers' => $followers,
            ]);
        }

        // Handle file upload
        if ($request->hasFile('sample_content')) {
            $file = $request->file('sample_content');
            $filename = 'registrations/sample_' . $registration->id . '_' . time() . '.' . ($file->getClientOriginalExtension() ?: 'jpg');
            Storage::disk('public')->put($filename, file_get_contents($file->getPathname()));

            $registration->highlightPosts()->create([
                'post_url' => $filename,
                'post_type' => str_starts_with($file->getMimeType(), 'video') ? 'video' : 'image',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Application submitted! We will notify you via email once reviewed. Please check your inbox regularly.',
        ]);
    }
}
