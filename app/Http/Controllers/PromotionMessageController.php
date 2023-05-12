<?php

namespace App\Http\Controllers;

use App\Models\PromotionMessage;
use Illuminate\Http\Request;

class PromotionMessageController extends Controller
{
    
    public function view(Request $request){

        $promotion_messages = PromotionMessage::with('store');

        if(isset($request->search)){

            $promotion_messages->whereRelation('store','name','LIKE',"%".$request->search."%");
        }

        $promotion_messages = $promotion_messages->latest()->paginate(10);

        return view('newDashboard.bot.promotion_messages',compact('promotion_messages'));
    }
}
