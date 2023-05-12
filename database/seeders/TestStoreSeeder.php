<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Store;
use App\Models\StoreSettings;
use App\Models\Subscription;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TestStoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $customerData = [
            'uuid' => '',
            'first_name' => 'test',
            'last_name' => 'test',
            'phone_number' => '000000',
        ];

        $customer = Customer::query()->create($customerData);

        $orderData = [
            'customer_id' => $customer->id,
            'first_name' => 'test',
            'last_name' => 'test',
            'governorate' => 'Damascus',
            'address' => '',
            'phone_number' => '0000',
            'total' => '',
            'payment_method' => 'haram',
            'payment_ref' => null,
            'payment_info' => '',
            'platform' => 'Website',
            'notes' => null,
            'additional_question1' => null,
            'additional_question2' => null,
            'currency' => 'SYP',
            'store_id' => Store::where('name','eCart')->first()->id,
        ];

        $order = Order::query()->create($orderData);


        $storeData = [
            'name' => 'Test Store',
            'type' => 'store',
            'domain' => 'TestStore.com',
            'token' => Str::random(),
            'is_active' => true,
            'order_id' => $order->id,
            'expires_at' => Carbon::now()->addMonth(),
        ];

        $store = Store::query()->create($storeData);

        $userData = [
            'name' => 'test admin',
            'phone_number' => '',
            'email' => 'test_admin@ecart.com',
            'password' => bcrypt('admin123'),
            'store_id' => $store->id,
        ];
        $user = User::query()->create($userData);

        $user->assignRole('store-owner');

      
  
        $order_total = 0;
        $order_fees = 0;
        $order_tax = 0;
        $ifHourEnabled = false;
        $hourFormat = '';

        $subscription_products = Product::where('name','Telegram Bot')->orWhere('name','Messenger Bot')->orWhere('name','Website Widget')->get();

        foreach ($subscription_products as $product) {
            $item_total = 0;
            $item_fees = 0;
            $item_tax = 0;

            $feesTaxInfo = $product->getFeesTax();

            $additional = [
                'checkin' => Carbon::now(),
            ];

            if ($product->require_end_date && ($item['end_date'] ?? null)) {
                $additional['checkout'] = Carbon::now()->addMonth();

                $diff = 0;
                $checkin = Carbon::parse($additional['checkin'])->timezone('Asia/Damascus');
                $checkout = Carbon::parse($additional['checkout'])->timezone('Asia/Damascus');

                while ($checkin <= $checkout) {
                    $diff++;
                    $checkin->setTime($checkout->hour, $checkout->minute)->addDay();
                }

                $item_total += $diff * $product->price;
                $item_fees = $feesTaxInfo['fees'] * $diff;
                $item_tax = $feesTaxInfo['tax'] * $diff;
            } else {
                $item_total += $product->price;
                $item_fees = $feesTaxInfo['fees'];
                $item_tax = $feesTaxInfo['tax'];
            }

            OrderItem::create([
                'product_id' => $product->id,
                'order_id' => $order->id,
                'price' => $product->price,
                'fees_amount' => $feesTaxInfo['fees'],
                'tax_amount' => $feesTaxInfo['tax'],
                'total' => $item_total + $item_fees + $item_tax,
                'subtotal' => $item_total,
                'fees_amount_subtotal' => $item_fees,
                'tax_amount_subtotal' => $item_tax,
                'quantity' => 1,
                'additional' => $additional,
            ]);

            $order_total += $item_total;
            $order_fees += $item_fees;
            $order_tax += $item_tax;
        }

        $order->update([
            'fees_amount' => $order_fees,
            'tax_amount' => $order_tax,
            'subtotal' => $order_total,
            'total' => $order_total + $order_fees + $order_tax,
        ]);

        $subscription = Subscription::create([
            'order_id' => $order->id,
            'store_id' => $user->store->id,
            'approved_at' => Carbon::now(),
        ]);


        $permissions = [
            'telegram_bot',
            'messenger_bot',
            'website_widget',
        ];


        foreach($permissions as $item){

            $p = DB::table('permissions')->where('name',$item)->first();
     
            DB::table('model_has_permissions')
            ->insert(['model_id' => $user->id,'model_type' => 'App\Models\User','permission_id' => $p->id,'expires_at' => Carbon::now()->addMonth()]);
        }
     


        $data = [
            "key" => "payment_method",
            "value" => [
                "cod" => [
                    "enabled" => true
                ]
            ],
            "store_id" => $store->id,
        ];

        StoreSettings::create($data);

        $category = Category::query()->create([
            'name' => 'test category',
            'store_id' => $store->id,
        ]);
        $products = [
            [
                'name' => 'test product 1',
                'type' => 'product',
                'require_end_date' => false,
                'image_url' => '#',
                'unit' => 'quantity',
                'price' => 10000,
               
            ], [
                'name' => 'test product 2',
                'type' => 'product',
                'require_end_date' => false,
                'image_url' => '#',
                'unit' => 'quantity',
                'price' => 10000,
             
            ], [
                'name' => 'test product 3',
                'type' => 'product',
                'require_end_date' => false,
                'image_url' => '#',
                'unit' => 'quantity',
                'price' => 10000,
             
            ],
        ];

        foreach ($products as $product)
            Product::query()->create(['store_id' => $store->id, 'category_id' => $category->id] + $product);
    }
}
