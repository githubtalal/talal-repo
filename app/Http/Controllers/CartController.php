<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = cart();
        return response()->json([
            'data' => $cart,
            'success' => true,
        ]);
    }

    public function addToCart()
    {
        $cart = cart();
        $product_id = request()->get('product_id');
        if (!$cart)
            $cart = create_cart();
        // TODO: Add to cart
    }

    public function removeFromCart($cartItemId)
    {
        $cart = cart();
        $error = false;
        if ($cart ){
            $item = $cart->items()->find($cartItemId);
            if ($item)  {
                $item->delete();
                return response()->json([
                    'success' => true,
                    'message' => 'Deleted',
                ]);
            }
        }
        return response()->json([
            'success' => false,
            'message' => 'Something went wrong...',
        ]);
    }
}
