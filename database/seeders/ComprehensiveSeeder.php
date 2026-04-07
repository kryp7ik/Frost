<?php

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\Auth\Role;
use App\Models\Auth\User;
use App\Models\Comment;
use App\Models\Store\Customer;
use App\Models\Store\Discount;
use App\Models\Store\Ingredient;
use App\Models\Store\LiquidProduct;
use App\Models\Store\Payment;
use App\Models\Store\Product;
use App\Models\Store\ProductInstance;
use App\Models\Store\Recipe;
use App\Models\Store\Shift;
use App\Models\Store\Shipment;
use App\Models\Store\ShopOrder;
use App\Models\Store\Transfer;
use Illuminate\Database\Seeder;

class ComprehensiveSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Seeding staff users...');
        $users = $this->seedUsers();

        $this->command->info('Seeding customers...');
        $customers = Customer::factory()->count(100)->create();

        $this->command->info('Seeding products & instances...');
        $productInstances = $this->seedProducts();

        $this->command->info('Seeding ingredients...');
        $ingredients = Ingredient::factory()->count(40)->create();

        $this->command->info('Seeding recipes & attaching ingredients...');
        $this->call(RecipeSeeder::class);
        $recipes = Recipe::all();
        $this->attachIngredientsToRecipes($recipes, $ingredients);

        $this->command->info('Seeding discounts...');
        $discounts = Discount::factory()->count(12)->create();

        $this->command->info('Seeding shop orders with products, liquids, discounts & payments...');
        $this->seedOrders($users, $customers, $productInstances, $recipes, $discounts);

        $this->command->info('Seeding shifts...');
        $this->seedShifts($users);

        $this->command->info('Seeding shipments...');
        $this->seedShipments($productInstances);

        $this->command->info('Seeding transfers...');
        $this->seedTransfers($productInstances);

        $this->command->info('Seeding announcements & comments...');
        $this->seedAnnouncements($users);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection<int, User>
     */
    private function seedUsers(): \Illuminate\Database\Eloquent\Collection
    {
        $managerRole = Role::where('name', 'manager')->first();

        // 3 managers across stores
        $managers = User::factory()->count(3)->create()->each(function (User $user, int $index) use ($managerRole) {
            $user->roles()->attach($managerRole);
            $user->forceFill(['store' => $index + 1])->save();
        });

        // 7 regular staff across stores
        $staff = User::factory()->count(7)->create()->each(function (User $user) {
            $user->forceFill(['store' => fake()->randomElement([1, 2, 3])])->save();
        });

        return User::all();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection<int, ProductInstance>
     */
    private function seedProducts(): \Illuminate\Database\Eloquent\Collection
    {
        $products = Product::factory()->count(30)->create();

        foreach ($products as $product) {
            foreach ([1, 2, 3] as $store) {
                ProductInstance::factory()->create([
                    'product_id' => $product->id,
                    'store' => $store,
                ]);
            }
        }

        return ProductInstance::all();
    }

    /**
     * @param \Illuminate\Database\Eloquent\Collection<int, Recipe> $recipes
     * @param \Illuminate\Database\Eloquent\Collection<int, Ingredient> $ingredients
     */
    private function attachIngredientsToRecipes($recipes, $ingredients): void
    {
        foreach ($recipes as $recipe) {
            $selected = $ingredients->random(rand(2, 5));
            $pivotData = [];
            foreach ($selected as $ingredient) {
                $pivotData[$ingredient->id] = ['amount' => rand(1, 20)];
            }
            $recipe->ingredients()->attach($pivotData);
        }
    }

    private function seedOrders($users, $customers, $productInstances, $recipes, $discounts): void
    {
        $activeRecipes = $recipes->where('active', true);

        for ($i = 0; $i < 150; $i++) {
            $store = fake()->randomElement([1, 2, 3]);
            $storeInstances = $productInstances->where('store', $store)->where('active', true);

            if ($storeInstances->isEmpty()) {
                continue;
            }

            $order = ShopOrder::factory()->create([
                'store' => $store,
                'user_id' => $users->random()->id,
                'customer_id' => $customers->random()->id,
                'created_at' => fake()->dateTimeBetween('-90 days', 'now'),
            ]);

            // Attach 1-4 product instances with quantities
            $orderProducts = $storeInstances->random(min(rand(1, 4), $storeInstances->count()));
            foreach ($orderProducts as $instance) {
                $order->productInstances()->attach($instance->id, [
                    'quantity' => rand(1, 3),
                ]);
            }

            // 60% of orders include liquid products
            if (fake()->boolean(60) && $activeRecipes->isNotEmpty()) {
                $liquidCount = rand(1, 3);
                for ($j = 0; $j < $liquidCount; $j++) {
                    LiquidProduct::factory()->create([
                        'shop_order_id' => $order->id,
                        'recipe_id' => $activeRecipes->random()->id,
                        'store' => $store,
                    ]);
                }
            }

            // 30% of orders get a discount
            if (fake()->boolean(30) && $discounts->isNotEmpty()) {
                $discount = $discounts->random();
                $applied = $discount->type === 'percent'
                    ? round($order->subtotal * ($discount->amount / 100), 2)
                    : $discount->amount;
                $order->discounts()->attach($discount->id, [
                    'applied' => $applied,
                ]);
            }

            // Payments for completed orders
            if ($order->complete) {
                $remaining = (float) $order->total;
                if (fake()->boolean(20) && $remaining > 20) {
                    // Split payment
                    $firstAmount = round($remaining * fake()->randomFloat(2, 0.3, 0.7), 2);
                    Payment::factory()->create([
                        'shop_order_id' => $order->id,
                        'type' => 'cash',
                        'amount' => $firstAmount,
                    ]);
                    Payment::factory()->create([
                        'shop_order_id' => $order->id,
                        'type' => 'card',
                        'amount' => round($remaining - $firstAmount, 2),
                    ]);
                } else {
                    Payment::factory()->create([
                        'shop_order_id' => $order->id,
                        'type' => fake()->randomElement(['cash', 'card']),
                        'amount' => $remaining,
                    ]);
                }
            }
        }
    }

    private function seedShifts($users): void
    {
        $staffUsers = $users->filter(fn (User $user) => !$user->hasRole('admin'));

        foreach ($staffUsers as $user) {
            Shift::factory()->count(rand(15, 25))->create([
                'user_id' => $user->id,
            ]);
        }
    }

    private function seedShipments($productInstances): void
    {
        for ($i = 0; $i < 20; $i++) {
            $store = fake()->randomElement([1, 2, 3]);
            $shipment = Shipment::factory()->create(['store' => $store]);

            $storeInstances = $productInstances->where('store', $store);
            if ($storeInstances->isEmpty()) {
                continue;
            }

            $items = $storeInstances->random(min(rand(3, 8), $storeInstances->count()));
            foreach ($items as $instance) {
                $shipment->productInstances()->attach($instance->id, [
                    'quantity' => rand(5, 25),
                ]);
            }
        }
    }

    private function seedTransfers($productInstances): void
    {
        for ($i = 0; $i < 15; $i++) {
            $transfer = Transfer::factory()->create();

            $fromInstances = $productInstances->where('store', $transfer->from_store);
            if ($fromInstances->isEmpty()) {
                continue;
            }

            $items = $fromInstances->random(min(rand(2, 5), $fromInstances->count()));
            foreach ($items as $instance) {
                $transfer->productInstances()->attach($instance->id, [
                    'quantity' => rand(1, 10),
                    'received' => $transfer->received,
                ]);
            }
        }
    }

    private function seedAnnouncements($users): void
    {
        $announcements = Announcement::factory()->count(15)->create([
            'user_id' => fn () => $users->random()->id,
        ]);

        foreach ($announcements as $announcement) {
            Comment::factory()->count(rand(1, 5))->create([
                'announcement_id' => $announcement->id,
                'user_id' => fn () => $users->random()->id,
            ]);
        }
    }
}
