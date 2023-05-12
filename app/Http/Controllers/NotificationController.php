<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Svg\Gradient\Stop;

class NotificationController extends Controller
{

    public function send_notification_form()
    {

        return view('newDashboard.notifications.send-form');
    }


    public function send_notification(Request $request)
    {

        $request->validate([
            'store_id' => ['required'],
            'store_id.*' => ['exists:stores,id'],
            'message' => ['required'],
            'url' => ['url','nullable'],
        ]);

       
        $url = route('dashboard');

        if(isset($request->url)){

            $url = $request->url;
        }

        foreach ($request->store_id as $store_id) {

            $store = Store::find($store_id);

            $notifiable = $store->user->first();

            $notifiable->notify(new \App\Notifications\CustomNotification(['text' => $request->message, 'url' => $url]));
        }


        return redirect()->back()->with('success', __('app.notification.success_send_message'));
    }


    public function show_notification($id)
    {

        $notification = DB::table('notifications')->where('notifiable_id', auth()->user()->id)->where('id', $id)->first();

     
        if (!isset($notification)) {

            abort(404);
        }

        if (!isset($notification->read_at)) {

            DB::table('notifications')->where('notifiable_id', auth()->user()->id)->where('id', $id)->update(['read_at' => now()]);
        }


        return redirect()->to(json_decode($notification->data)->url);
    }


    public function view_all_notifications(){

    
        $notifications = DB::table('notifications')->where('notifiable_id', auth()->user()->id)->latest()->paginate(10);

        return view('newDashboard.notifications.view_all',compact('notifications'));
    }


    public function get_notifiable_stores(Request $request)
    {

        $page = $request->get('page');
        $resultCount = 10;

        $offset = ($page - 1) * $resultCount;

        $stores = Store::where('name', 'LIKE',  '%' . $request->get("term") . '%')->where('name', '!=', 'eCart')->orderBy('name')->skip($offset)->take($resultCount)->get(['id', DB::raw('name as text')]);

        $count = Count(Store::where('name', 'LIKE',  '%' . $request->get("term") . '%')->orderBy('name')->get(['id', DB::raw('name as text')]));
        $endCount = $offset + $resultCount;
        $morePages = $count > $endCount;

        $results = array(
            "results" => $stores,
            "pagination" => array(
                "more" => $morePages
            )
        );

        return response()->json($results);
    }
}
