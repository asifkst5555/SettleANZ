<?php
// Upload this to your LIVE server's public/ folder as: diagnose_mail.php
// Then visit: https://settleanz.com/diagnose_mail.php
// DELETE THIS FILE after testing!

header('Content-Type: text/plain; charset=utf-8');
echo "=== SettleANZ Live Mail Diagnostic ===\n";
echo "Time: " . date('Y-m-d H:i:s') . "\n\n";

// Step 1: Boot Laravel
try {
    define('LARAVEL_START', microtime(true));
    
    // Try common Laravel directory structures
    $basePaths = [
        __DIR__ . '/..',                    // standard: public/ is inside project root
        __DIR__ . '/../../',                // some hosts
        __DIR__,                            // everything in public_html
        '/home/u821611941/domains/settleanz.com',
        '/home/u821611941/domains/settleanz.com/public_html',
        '/home/u821611941/settleanz',
        '/home/u821611941/public_html',
    ];
    
    // Also scan for vendor in parent directories
    $dir = __DIR__;
    for ($i = 0; $i < 5; $i++) {
        $basePaths[] = $dir;
        $dir = dirname($dir);
    }
    $basePaths = array_unique(array_map('realpath', array_filter($basePaths)));
    
    $vendorPath = null;
    $bootstrapPath = null;
    foreach ($basePaths as $base) {
        if (file_exists($base . '/vendor/autoload.php') && file_exists($base . '/bootstrap/app.php')) {
            $vendorPath = $base . '/vendor/autoload.php';
            $bootstrapPath = $base . '/bootstrap/app.php';
            break;
        }
    }
    
    if (!$vendorPath) {
        echo "ERROR: Cannot find Laravel installation.\n";
        echo "Tried paths:\n";
        foreach ($basePaths as $base) {
            echo "  - " . realpath($base) . " (vendor exists: " . (file_exists($base . '/vendor/autoload.php') ? 'YES' : 'NO') . ")\n";
        }
        exit(1);
    }
    
    echo "Laravel root: " . realpath(dirname($vendorPath)) . "\n\n";
    
    require $vendorPath;
    $app = require $bootstrapPath;
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
} catch (\Throwable $e) {
    echo "BOOTSTRAP FAILED: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    exit(1);
}

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;

// Step 2: Check .env settings
echo "=== ENV CONFIG (before applyMailConfig) ===\n";
echo "APP_URL: " . config('app.url') . "\n";
echo "mail.default: " . config('mail.default') . "\n";
echo "mail.mailers.smtp.host: " . config('mail.mailers.smtp.host') . "\n";
echo "mail.mailers.smtp.port: " . config('mail.mailers.smtp.port') . "\n";
echo "mail.mailers.smtp.scheme: " . var_export(config('mail.mailers.smtp.scheme'), true) . "\n";
echo "mail.from.address: " . config('mail.from.address') . "\n\n";

// Step 3: Check database settings
echo "=== DATABASE SETTINGS (site_settings table) ===\n";
try {
    $keys = ['mail_mailer', 'smtp_host', 'smtp_port', 'smtp_username', 'smtp_password', 'mail_encryption', 'mail_from_address', 'mail_from_name'];
    foreach ($keys as $key) {
        $val = App\Models\SiteSetting::getValue($key, '(not set)');
        if ($key === 'smtp_password') {
            echo "$key: " . str_repeat('*', max(0, strlen($val) - 4)) . substr($val, -4) . " (length: " . strlen($val) . ")\n";
        } else {
            echo "$key: $val\n";
        }
    }
} catch (\Throwable $e) {
    echo "DB ERROR: " . $e->getMessage() . "\n";
}

// Step 4: Apply mail config and check
echo "\n=== AFTER applyMailConfig ===\n";
try {
    $emailService = app(App\Services\EmailService::class);
    $emailService->applyMailConfig();
    
    echo "mail.default: " . config('mail.default') . "\n";
    echo "mail.mailers.smtp.host: " . config('mail.mailers.smtp.host') . "\n";
    echo "mail.mailers.smtp.port: " . config('mail.mailers.smtp.port') . "\n";
    echo "mail.mailers.smtp.scheme: " . var_export(config('mail.mailers.smtp.scheme'), true) . "\n";
    echo "mail.mailers.smtp.username: " . config('mail.mailers.smtp.username') . "\n";
    echo "mail.from.address: " . config('mail.from.address') . "\n";
} catch (\Throwable $e) {
    echo "applyMailConfig FAILED: " . $e->getMessage() . "\n";
}

// Step 5: Check transport
echo "\n=== TRANSPORT CHECK ===\n";
try {
    $mailer = Mail::mailer();
    $transport = $mailer->getSymfonyTransport();
    echo "Transport class: " . get_class($transport) . "\n";
    echo "Transport: " . $transport . "\n";
} catch (\Throwable $e) {
    echo "Transport error: " . $e->getMessage() . "\n";
}

// Step 6: Check ebook exists
echo "\n=== EBOOK CHECK ===\n";
try {
    $slug = config('ebook.default_ebook_slug', 'settleanZ-new-arrival-checklist');
    $ebook = App\Models\Ebook::where('slug', $slug)->where('status', 'published')->first();
    if ($ebook) {
        echo "Default ebook found: {$ebook->title} (ID: {$ebook->id}, slug: {$ebook->slug})\n";
    } else {
        $any = App\Models\Ebook::published()->first();
        if ($any) {
            echo "Default slug '$slug' NOT found, but found: {$any->title} (slug: {$any->slug})\n";
        } else {
            echo "NO published ebook found! This will cause form submission to fail!\n";
        }
    }
    echo "Total ebooks: " . App\Models\Ebook::count() . "\n";
    echo "Published ebooks: " . App\Models\Ebook::where('status', 'published')->count() . "\n";
} catch (\Throwable $e) {
    echo "Ebook check error: " . $e->getMessage() . "\n";
}

// Step 7: Check email_logs
echo "\n=== RECENT EMAIL LOGS ===\n";
try {
    $logs = App\Models\EmailLog::orderByDesc('id')->take(5)->get();
    if ($logs->isEmpty()) {
        echo "No email logs found.\n";
    }
    foreach ($logs as $log) {
        echo "ID:{$log->id} | To:{$log->to_email} | Status:{$log->status} | Error:{$log->error_message} | At:{$log->created_at}\n";
    }
} catch (\Throwable $e) {
    echo "EmailLog error: " . $e->getMessage() . "\n";
}

// Step 8: Try to send test email
echo "\n=== SENDING TEST EMAIL ===\n";
try {
    $testHtml = '<h2>SettleANZ Live Server Test</h2><p>Sent at: ' . now() . '</p><p>This confirms your live SMTP is working.</p>';
    
    Mail::html($testHtml, function ($message) {
        $message->to('asifkst5555@gmail.com')
            ->subject('[LIVE TEST] SettleANZ SMTP Working');
    });
    
    echo "SUCCESS: Test email sent via SMTP!\n";
    echo "Check asifkst5555@gmail.com inbox for '[LIVE TEST] SettleANZ SMTP Working'\n";
} catch (\Throwable $e) {
    echo "SEND FAILED!\n";
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n\n";
    
    // If it's an auth error, suggest fix
    if (str_contains($e->getMessage(), 'auth') || str_contains($e->getMessage(), '535') || str_contains($e->getMessage(), 'password')) {
        echo ">>> This is an AUTHENTICATION error. Your SMTP password is incorrect.\n";
        echo ">>> Go to Hostinger hPanel > Emails > info@settleanz.com > Reset password.\n";
    } elseif (str_contains($e->getMessage(), 'tls') || str_contains($e->getMessage(), 'ssl') || str_contains($e->getMessage(), 'version')) {
        echo ">>> This is a TLS/SSL error. Try changing mail_encryption to 'ssl' and smtp_port to 465.\n";
    } elseif (str_contains($e->getMessage(), 'connect') || str_contains($e->getMessage(), 'timeout')) {
        echo ">>> Cannot connect to SMTP server. Your host may be blocking port 587.\n";
    }
}

// Step 9: Check laravel.log for recent errors
echo "\n=== RECENT LARAVEL LOG ERRORS ===\n";
try {
    $logFile = storage_path('logs/laravel.log');
    if (file_exists($logFile)) {
        $lines = file($logFile);
        $errorLines = [];
        foreach (array_slice($lines, -100) as $line) {
            if (preg_match('/\.(ERROR|CRITICAL|EMERGENCY)/', $line)) {
                $errorLines[] = trim($line);
            }
        }
        if (empty($errorLines)) {
            echo "No recent errors in log.\n";
        } else {
            echo "Found " . count($errorLines) . " recent error(s):\n";
            foreach (array_slice($errorLines, -5) as $el) {
                echo "  " . substr($el, 0, 300) . "\n";
            }
        }
    } else {
        echo "Log file not found at: $logFile\n";
    }
} catch (\Throwable $e) {
    echo "Log check error: " . $e->getMessage() . "\n";
}

echo "\n=== DONE ===\n";
echo "IMPORTANT: Delete this file (diagnose_mail.php) from your public/ folder after testing!\n";
