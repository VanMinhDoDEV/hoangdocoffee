<?php
use Illuminate\Support\Facades\Schema;

echo "Checking is_featured column in products table...\n";
if (Schema::hasColumn('products', 'is_featured')) {
    echo "Column 'is_featured' exists.\n";
} else {
    echo "Column 'is_featured' DOES NOT exist.\n";
}
