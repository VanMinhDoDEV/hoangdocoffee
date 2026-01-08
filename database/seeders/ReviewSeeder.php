<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::all();
        $users = User::all();

        if ($products->count() === 0) {
            $this->command->info('No products found. Skipping review seeding.');
            return;
        }

        foreach ($products as $product) {
            // Create 0-5 reviews for each product
            $reviewCount = rand(0, 5);
            
            for ($i = 0; $i < $reviewCount; $i++) {
                $user = $users->random();
                $rating = rand(1, 5);
                $status = fake()->randomElement(['pending', 'published', 'hidden']);
                
                Review::create([
                    'product_id' => $product->id,
                    'user_id' => $user ? $user->id : null,
                    'reviewer_name' => $user ? $user->name : fake()->name(),
                    'reviewer_email' => $user ? $user->email : fake()->email(),
                    'rating' => $rating,
                    'title' => fake()->sentence(4),
                    'content' => fake()->paragraph(),
                    'status' => $status,
                    'is_verified_purchase' => fake()->boolean(70),
                    'created_at' => fake()->dateTimeBetween('-1 year', 'now'),
                ]);
            }
        }
    }
}
