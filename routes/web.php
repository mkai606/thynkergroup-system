<?php

use App\Http\Controllers\Admin\ApprovalController;
use App\Http\Controllers\Admin\CampaignController;
use App\Http\Controllers\Admin\PayoutController;
use App\Http\Controllers\Admin\RegistrationController;
use App\Http\Controllers\Admin\SidekickController;
use App\Http\Controllers\Admin\BroadcastController;
use App\Http\Controllers\Admin\IntelController;
use App\Http\Controllers\Admin\LeaderboardController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\VipController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use Illuminate\Support\Facades\Route;

// ──────────────────────────────────────────────
// Guest Routes
// ──────────────────────────────────────────────

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

// ──────────────────────────────────────────────
// Authenticated Routes
// ──────────────────────────────────────────────

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

    // Root redirect based on role
    Route::get('/', function () {
        return match (auth()->user()->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'agent' => redirect()->route('agent.dashboard'),
            default => redirect()->route('login'),
        };
    })->name('home');
});

// ──────────────────────────────────────────────
// Admin Routes
// ──────────────────────────────────────────────

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    // Campaign Ops
    Route::get('/campaigns', [CampaignController::class, 'index'])->name('campaigns');
    Route::get('/campaigns/create', [CampaignController::class, 'create'])->name('campaigns.create');
    Route::post('/campaigns', [CampaignController::class, 'store'])->name('campaigns.store');
    Route::get('/campaigns/{campaign}', [CampaignController::class, 'show'])->name('campaigns.show');
    Route::post('/campaigns/{campaign}/tasks/{task}/toggle-lock', [CampaignController::class, 'toggleLock'])->name('campaigns.toggleLock');
    Route::post('/campaigns/{campaign}/auto-accept', [CampaignController::class, 'autoAccept'])->name('campaigns.autoAccept');
    Route::post('/campaigns/{campaign}/applications/{application}/accept', [CampaignController::class, 'acceptApplication'])->name('campaigns.applications.accept');
    Route::post('/campaigns/{campaign}/applications/{application}/reject', [CampaignController::class, 'rejectApplication'])->name('campaigns.applications.reject');

    // Approvals (Verification Queue)
    Route::get('/approvals', [ApprovalController::class, 'index'])->name('approvals');
    Route::get('/approvals/{approval}', [ApprovalController::class, 'show'])->name('approvals.show');
    Route::post('/approvals/{approval}/approve', [ApprovalController::class, 'approve'])->name('approvals.approve');
    Route::post('/approvals/{approval}/mark-paid', [ApprovalController::class, 'markPaid'])->name('approvals.markPaid');
    Route::post('/approvals/{approval}/reject', [ApprovalController::class, 'reject'])->name('approvals.reject');

    // Registrations (Recruitment Queue)
    Route::get('/registrations', [RegistrationController::class, 'index'])->name('registrations');
    Route::get('/registrations/{registration}', [RegistrationController::class, 'show'])->name('registrations.show');
    Route::post('/registrations/{registration}/approve', [RegistrationController::class, 'approve'])->name('registrations.approve');
    Route::post('/registrations/{registration}/reject', [RegistrationController::class, 'reject'])->name('registrations.reject');

    // Sidekick Hub
    Route::get('/sidekicks', [SidekickController::class, 'index'])->name('sidekicks');
    Route::get('/sidekicks/{user}', [SidekickController::class, 'show'])->name('sidekicks.show');
    Route::post('/sidekicks/{user}/flag', [SidekickController::class, 'flag'])->name('sidekicks.flag');
    Route::post('/sidekicks/{user}/unflag', [SidekickController::class, 'unflag'])->name('sidekicks.unflag');

    // VIP Requests
    Route::get('/vip', [VipController::class, 'index'])->name('vip');
    Route::get('/vip/{vip}', [VipController::class, 'show'])->name('vip.show');
    Route::post('/vip/{vip}/approve', [VipController::class, 'approve'])->name('vip.approve');
    Route::post('/vip/{vip}/reject', [VipController::class, 'reject'])->name('vip.reject');

    // Broadcasts
    Route::get('/broadcasts', [BroadcastController::class, 'index'])->name('broadcasts');
    Route::post('/broadcasts', [BroadcastController::class, 'store'])->name('broadcasts.store');

    // Intel (Analytics)
    Route::get('/intel', [IntelController::class, 'index'])->name('intel');

    // Settings
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
    Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::post('/settings/tng-qr', [SettingsController::class, 'uploadTngQr'])->name('settings.uploadTngQr');

    // Payouts
    Route::get('/payouts', [PayoutController::class, 'index'])->name('payouts');
    Route::get('/payouts/{payout}', [PayoutController::class, 'show'])->name('payouts.show');
    Route::post('/payouts/{payout}/process', [PayoutController::class, 'process'])->name('payouts.process');
    Route::post('/payouts/{payout}/complete', [PayoutController::class, 'complete'])->name('payouts.complete');
    Route::post('/payouts/{payout}/reject', [PayoutController::class, 'reject'])->name('payouts.reject');

    // Leaderboard
    Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard');
});

// ──────────────────────────────────────────────
// Agent Routes
// ──────────────────────────────────────────────

Route::middleware(['auth', 'role:agent'])->prefix('agent')->name('agent.')->group(function () {
    // Feed
    Route::get('/dashboard', [App\Http\Controllers\Agent\FeedController::class, 'index'])->name('dashboard');

    // Notifications
    Route::post('/notifications/read', [App\Http\Controllers\Agent\FeedController::class, 'markNotificationsRead'])->name('notifications.read');

    // Task Detail + Actions
    Route::get('/tasks/{task}', [App\Http\Controllers\Agent\FeedController::class, 'show'])->name('tasks.show');
    Route::post('/tasks/{task}/apply', [App\Http\Controllers\Agent\FeedController::class, 'apply'])->name('tasks.apply');
    Route::post('/tasks/{task}/submit', [App\Http\Controllers\Agent\FeedController::class, 'submit'])->name('tasks.submit');

    // Ops
    Route::get('/ops', [App\Http\Controllers\Agent\OpsController::class, 'index'])->name('ops');

    // Wallet
    Route::get('/wallet', [App\Http\Controllers\Agent\WalletController::class, 'index'])->name('wallet');

    // Leaderboard
    Route::get('/leaderboard', [App\Http\Controllers\Agent\LeaderboardController::class, 'index'])->name('leaderboard');

    // VIP Upgrade
    Route::post('/vip/request', [App\Http\Controllers\Agent\ProfileController::class, 'requestVip'])->name('vip.request');

    // Profile
    Route::get('/profile', [App\Http\Controllers\Agent\ProfileController::class, 'index'])->name('profile');
    Route::post('/profile/avatar', [App\Http\Controllers\Agent\ProfileController::class, 'updateAvatar'])->name('profile.avatar');
    Route::post('/profile/tng-qr', [App\Http\Controllers\Agent\ProfileController::class, 'updateTngQr'])->name('profile.tng-qr');
    Route::post('/logout', [App\Http\Controllers\Auth\LogoutController::class, 'logout'])->name('logout');
});
