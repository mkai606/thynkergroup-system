<?php
/**
 * SidekickHQ — One-time setup script for cPanel (no terminal)
 *
 * Access via: https://thynkergroups.com/setup.php?key=THYNKER2026
 * DELETE THIS FILE after setup is complete!
 */

// Security key — prevent unauthorized access
if (($_GET['key'] ?? '') !== 'THYNKER2026') {
    http_response_code(403);
    die('Unauthorized');
}

// Bootstrap Laravel
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo '<pre style="background:#1a1a1a;color:#aaff00;padding:20px;font-family:monospace;font-size:13px;">';
echo "=== SIDEKICKHQ SETUP ===\n\n";

$step = $_GET['step'] ?? 'menu';

if ($step === 'menu') {
    echo "Available steps:\n\n";
    echo "1. <a style='color:#00f0ff' href='?key=THYNKER2026&step=key'>Generate APP_KEY</a>\n";
    echo "2. <a style='color:#00f0ff' href='?key=THYNKER2026&step=migrate'>Run Migrations</a>\n";
    echo "3. <a style='color:#00f0ff' href='?key=THYNKER2026&step=seed'>Run Seeders</a>\n";
    echo "4. <a style='color:#00f0ff' href='?key=THYNKER2026&step=storage'>Create Storage Symlink</a>\n";
    echo "5. <a style='color:#00f0ff' href='?key=THYNKER2026&step=optimize'>Optimize (cache config/routes)</a>\n";
    echo "6. <a style='color:#00f0ff' href='?key=THYNKER2026&step=all'>Run ALL steps</a>\n";
    echo "\n<a style='color:#ff4444' href='?key=THYNKER2026&step=fresh'>DANGER: Fresh migrate + seed (drops all tables)</a>\n";
}

if ($step === 'key' || $step === 'all') {
    echo "[1/5] Generating APP_KEY...\n";
    Artisan::call('key:generate', ['--force' => true]);
    echo Artisan::output();
    echo "Done.\n\n";
}

if ($step === 'migrate' || $step === 'all') {
    echo "[2/5] Running migrations...\n";
    Artisan::call('migrate', ['--force' => true]);
    echo Artisan::output();
    echo "Done.\n\n";
}

if ($step === 'seed' || $step === 'all') {
    echo "[3/5] Running seeders...\n";
    Artisan::call('db:seed', ['--force' => true]);
    echo Artisan::output();
    echo "Done.\n\n";
}

if ($step === 'storage' || $step === 'all') {
    echo "[4/5] Creating storage symlink...\n";
    // Manual symlink for cPanel
    $target = dirname(__DIR__) . '/storage/app/public';
    $link = __DIR__ . '/storage';
    if (is_link($link)) {
        unlink($link);
    }
    if (!file_exists($link)) {
        symlink($target, $link);
        echo "Symlink created: public/storage -> storage/app/public\n";
    } else {
        echo "Storage link already exists.\n";
    }
    echo "Done.\n\n";
}

if ($step === 'optimize' || $step === 'all') {
    echo "[5/5] Optimizing...\n";
    Artisan::call('optimize');
    echo Artisan::output();
    echo "Done.\n\n";
}

if ($step === 'fresh') {
    echo "FRESH MIGRATE + SEED...\n";
    Artisan::call('migrate:fresh', ['--force' => true, '--seed' => true]);
    echo Artisan::output();
    echo "Done.\n\n";
}

if ($step === 'all') {
    echo "=========================\n";
    echo "ALL SETUP COMPLETE!\n";
    echo "=========================\n\n";
    echo "NOW DELETE THIS FILE from File Manager!\n";
    echo "Path: public_html/setup.php\n";
}

echo '</pre>';
