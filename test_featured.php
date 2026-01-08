<?php
use App\Models\Product;

echo "Testing Featured Product Creation and Scope...\n";

try {
    $p = new Product();
    $p->name = 'Test Featured ' . time();
    $p->is_featured = true;
    $p->save();

    echo "Created product ID: " . $p->id . "\n";

    $count = Product::featured()->where('id', $p->id)->count();
    
    if ($count > 0) {
        echo "VERIFIED_FEATURED: Scope works correctly.\n";
    } else {
        echo "FAILED: Scope did not find the product.\n";
    }
    
    // Clean up
    $p->delete();
    echo "Cleaned up test product.\n";

} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
