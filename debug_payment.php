<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$products = \App\Models\Product::limit(5)->get();
foreach ($products as $product) {
    echo "Product ID: " . $product->id . "\n";
    echo "Payment Method: " . $product->payment_method . "\n";
    echo "Type: " . gettype($product->payment_method) . "\n";
    echo "-------------------\n";
}

$settingsPath = storage_path('app/settings.json');
if (file_exists($settingsPath)) {
    $settings = json_decode(file_get_contents($settingsPath), true);
    echo "Global Payment Settings:\n";
    print_r($settings['payment'] ?? 'No payment settings');
} else {
    echo "Settings file not found at $settingsPath\n";
}
