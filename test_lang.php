<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

app()->setLocale('en');
echo "Current Locale: " . app()->getLocale() . "\n";
echo "EN Translation: " . __('messages.display_options') . "\n";

app()->setLocale('vi');
echo "Current Locale: " . app()->getLocale() . "\n";
echo "VI Translation: " . __('messages.display_options') . "\n";
