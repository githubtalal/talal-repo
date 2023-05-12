<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\OrderItem;

class PriceOrderItemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $orderItems = OrderItem::withTrashed()->get();
        foreach ($orderItems as $orderItem){
            if (!$orderItem->price){
                $price = $orderItem->total / $orderItem->quantity;          
                $orderItem->update(['price' => $price]);
            }
        }
    }
}
