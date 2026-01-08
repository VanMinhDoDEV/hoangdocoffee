<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductImage;
use App\Models\Warehouse;
use App\Models\WarehouseInventory;
use App\Models\CustomerProfile;
use App\Models\Address;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            ['name' => 'Admin User', 'password' => bcrypt('admin123'), 'role' => 'admin']
        );

        $user = User::firstOrCreate(
            ['email' => 'customer@example.com'],
            ['name' => 'Demo Customer', 'password' => bcrypt('customer123'), 'role' => 'customer']
        );

        CustomerProfile::firstOrCreate(
            ['user_id' => $user->id],
            ['club_level' => 'basic', 'lifetime_value' => 0, 'reward_points' => 0]
        );

        Address::firstOrCreate(
            ['user_id' => $user->id, 'address_line' => '123 Đường ABC'],
            ['name' => 'Nhà riêng', 'phone' => '0900000000', 'city' => 'TP. HCM', 'is_default' => true]
        );

        $product = Product::firstOrCreate(
            ['slug' => 'cafe-robusta'],
            ['name' => 'Cà phê Robusta', 'material' => '100% Robusta', 'description' => 'Cà phê Robusta nguyên chất.']
        );

        $variants = [
            ['sku' => 'ROBUSTA-500G', 'weight' => '500g', 'roast' => 'Medium', 'price' => 150000, 'stock' => 100],
            ['sku' => 'ROBUSTA-250G', 'weight' => '250g', 'roast' => 'Medium', 'price' => 80000, 'stock' => 100],
        ];

        foreach ($variants as $data) {
            $variant = ProductVariant::firstOrCreate(
                ['sku' => $data['sku']],
                [
                    'product_id' => $product->id,
                    'price' => $data['price'],
                    'inventory_quantity' => $data['stock'],
                    'is_active' => true,
                ]
            );
            // Just create a placeholder image
             ProductImage::firstOrCreate(
                ['product_id' => $product->id, 'product_variant_id' => $variant->id, 'url' => '/images/coffee-robusta.jpg'],
                ['is_primary' => true, 'position' => 1]
            );
        }

        $main = Warehouse::firstOrCreate(['code' => 'MAIN'], ['name' => 'Main Warehouse', 'address' => '', 'is_active' => true]);
        $variantsAll = ProductVariant::where('product_id', $product->id)->get();
        foreach ($variantsAll as $v) {
            WarehouseInventory::firstOrCreate(['warehouse_id' => $main->id, 'product_variant_id' => $v->id], ['on_hand' => (int)($v->inventory_quantity ?? 0), 'reserved' => 0, 'incoming' => 0]);
        }
    }
}
