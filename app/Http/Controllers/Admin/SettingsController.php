<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = SystemSetting::firstOrCreate([], [
            'tier_config' => [], 'vip_config' => [], 'campaign_config' => [],
            'reward_config' => [], 'fraud_config' => [], 'payment_config' => [], 'ai_engine_config' => [],
        ]);

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $settings = SystemSetting::firstOrCreate([], [
            'tier_config' => [], 'vip_config' => [], 'campaign_config' => [],
            'reward_config' => [], 'fraud_config' => [], 'payment_config' => [], 'ai_engine_config' => [],
        ]);

        $settings->update([
            'tier_config' => [
                'tierA_minFollowers' => (int) $request->input('tierA_minFollowers', 10000),
                'tierB_minFollowers' => (int) $request->input('tierB_minFollowers', 5000),
                'tierC_minFollowers' => (int) $request->input('tierC_minFollowers', 3000),
                'tierD_minFollowers' => (int) $request->input('tierD_minFollowers', 2000),
                'tierE_minFollowers' => (int) $request->input('tierE_minFollowers', 0),
                'autoPromotion' => $request->boolean('autoPromotion'),
                'autoDowngrade' => $request->boolean('autoDowngrade'),
            ],
            'vip_config' => [
                'vipFee' => (float) $request->input('vipFee', 15.00),
                'minCampaigns' => (int) $request->input('minCampaigns', 5),
                'minReferrals' => (int) $request->input('minReferrals', 5),
                'vipDuration' => (int) $request->input('vipDuration', 365),
                'autoRenewal' => $request->boolean('autoRenewal'),
            ],
            'campaign_config' => [
                'maxSlots' => (int) $request->input('maxSlots', 50),
                'defaultPlatform' => $request->input('defaultPlatform', 'Instagram'),
                'allowMultiApply' => $request->boolean('allowMultiApply'),
                'autoCloseExpired' => $request->boolean('autoCloseExpired'),
            ],
        ]);

        return redirect()->route('admin.settings')->with('success', 'Settings updated.');
    }

    public function uploadTngQr(Request $request)
    {
        $request->validate([
            'tng_qr' => ['required', 'file', 'mimetypes:image/jpeg,image/png,image/webp', 'max:2048'],
        ]);

        $settings = SystemSetting::firstOrCreate([], [
            'tier_config' => [], 'vip_config' => [], 'campaign_config' => [],
            'reward_config' => [], 'fraud_config' => [], 'payment_config' => [], 'ai_engine_config' => [],
        ]);
        $paymentConfig = $settings->payment_config ?? [];

        // Delete old QR if exists
        if (!empty($paymentConfig['tng_qr_url'])) {
            Storage::disk('public')->delete($paymentConfig['tng_qr_url']);
        }

        $file = $request->file('tng_qr');
        $filename = 'tng_qr/admin_tng_' . time() . '.jpg';
        Storage::disk('public')->put($filename, file_get_contents($file->getPathname()));

        $paymentConfig['tng_qr_url'] = $filename;
        $settings->update(['payment_config' => $paymentConfig]);

        return redirect()->route('admin.settings')->with('success', 'TNG QR code uploaded.');
    }
}
