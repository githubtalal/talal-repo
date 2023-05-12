<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EcartStoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $storeData = [
            'name' => 'eCart',
            'type' => 'store',
            'domain' => 'ecart.com',
            'token' => Str::random(),
        ];
        $store = Store::query()->create($storeData);
        $userData = [
            'name' => 'eCart Admin',
            'phone_number' => '',
            'email' => 'admin@ecart.com',
            'password' => bcrypt('admin123'),
            'store_id' => $store->id,
        ];
        $user = User::query()->create($userData);

        $user->assignRole('super-admin');
        $category = Category::query()->create([
            'name' => 'Services',
            'store_id' => $store->id,
        ]);
        $products = [
            [
                'name' => 'Telegram Bot',
                'type' => 'product',
                'require_end_date' => false,
                'image_url' => '#',
                'unit' => 'quantity',
                'price' => 10000,
                'additional' => [
                    'permission_name' => 'telegram_bot'
                ],
            ], [
                'name' => 'Messenger Bot',
                'type' => 'product',
                'require_end_date' => false,
                'image_url' => '#',
                'unit' => 'quantity',
                'price' => 10000,
                'additional' => [
                    'permission_name' => 'messenger_bot'
                ],
            ], [
                'name' => 'Website Widget',
                'type' => 'product',
                'require_end_date' => false,
                'image_url' => '#',
                'unit' => 'quantity',
                'price' => 10000,
                'additional' => [
                    'permission_name' => 'website_widget'
                ],
            ],
        ];

        foreach ($products as $product)
            Product::query()->create(['store_id' => $store->id, 'category_id' => $category->id] + $product);
    }
}
