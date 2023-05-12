<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Store;
use App\Models\StoreSettings;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;

class StoreController extends Controller
{
    public function edit()
    {
        $store = auth()->user()->store;
        return view('newDashboard.settings', compact('store'));
    }

    public function update(Request $request)
    {
        $store = auth()->user()->store;

        if ($request->hasFile('logo')) {
            $store['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $slug = $request->get('slug') ? $request->get('slug') : $request->get('name');
        $slug = Str::slug($slug);

        $store->update([
            'domain' => $request->get('domain'),
            'button_text' => $request->get('btn_text'),
            'button_style' => $request->get('btn_style'),
            'name' => $request->get('name'),
            'slug' => $slug,
            'subname' => $request->get('subname')
        ]);

        return Redirect()->back()->with('store')->with('success_message', __('app.responses_messages.updated_successfully'));
    }

    public function accountSettings()
    {
        $user = auth()->user()->load('store');

        $ifEnabled = false;

        $bot_settings = StoreSettings::where([
            ['store_id', auth()->user()->store->id],
            ['key', 'bot_settings'],
        ])->first();

        if ($bot_settings) {
            if (array_key_exists('power_button', $bot_settings->value)) {
                $ifEnabled = true;
            }

        }

        return view('newDashboard.account-settings', compact('ifEnabled', 'user'));
    }

    public function buttonSettings()
    {   
        $messages = DB::table('translations')
        ->where('store_id', auth()->user()->store->id)
        ->get();

        return view('newDashboard.button-settings', compact('messages'));
    }

    public function store_button_settings(Request $request)
    {

        foreach($request->all() as $key => $value)
        {
            $affected = DB::table('translations')
            ->where('store_id', auth()->user()->store->id)
            ->where('key', str_replace('@', '.', $key))
            ->update(['value' => $value]);
            
            if($affected)
                DB::table('translations')
                ->where('store_id', auth()->user()->store->id)
                ->where('key', str_replace('@', '.', $key))
                ->update(['updated_at' => now()]);
        }

        return redirect()->route('buttonSettings.view')->with('success_message', __('app.responses_messages.saved_successfuly'));
    }

    public function store_account_settings(Request $request)
    {
        $user = auth()->user();

        $this->validate($request, [
            'store_name' => 'required',
            'store_type' => 'required',
            'store_manager' => 'required',
            'phone_number' => 'required',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'required_with:re_password|same:re_password',
            're_password' => 'required_with:password',
        ]);

        $user->update([
            'name' => $request->store_manager,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
        ]);

        if ($request->password && $request->re_password) {
            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        $user->store->update([
            'name' => $request->store_name,
            'type' => $request->store_type,
        ]);

        $bot_settings = StoreSettings::where([
            ['store_id', $user->store->id],
            ['key', 'bot_settings'],
        ])->first();

        if ($bot_settings) {
            $value = $bot_settings->value;

            if ($request->power_button) {
                $value['power_button'] = $request->power_button;
            } else {
                unset($value['power_button']);
            }

            $bot_settings->update(['value' => $value]);
            updateMenus($user->store->id);

        }
        return redirect()->route('accountSettings.view')->with('success_message', __('app.responses_messages.settings_saved_successfully'));
    }

    // payment methods
    public function createStoreSettings()
    {
        $payment_methods = [];
        $payments = StoreSettings::where([
            ['store_id', auth()->user()->store->id],
            ['key', 'payment_method'],
        ])->first();

        if ($payments) {
            $payment_methods = $payments->value;
        }

        $all_payment_methods = config('payment_methods');
        $all_payment_methods = array_unique($all_payment_methods);

        return view('newDashboard.add-store-settings', compact('payment_methods', 'all_payment_methods'));
    }

    // payment methods
    public function addStoreSettings(Request $request)
    {
        $store_id = auth()->user()->store->id;
        $value = $request->payment_method;
        // dd($value);
        $payment_methods = StoreSettings::where([
            ['key', 'payment_method'],
            ['store_id', $store_id],
        ])->first();

        if ($payment_methods != null) {
            $payment_methods->update(['value' => $value]);
        } else {
            $data = [
                "key" => "payment_method",
                "value" => $value,
                "store_id" => $store_id,
            ];
            StoreSettings::create($data);
        }

        return redirect()->route('createStoreSettings')->with('success_message', __('app.alert_messages.save.success', ['item' => trans_choice('app.payment_methods', 2)]));
    }

    // bot settings
    public function create_bot_settings()
    {
        $bot_settings = StoreSettings::where([
            ['store_id', auth()->user()->store->id],
            ['key', 'bot_settings'],
        ])->first();

        if ($bot_settings) {
            $bot_settings = $bot_settings->value;
        }

        return view('newDashboard.bot-settings', compact('bot_settings'));
    }

    // bot settings
    public function add_bot_settings(Request $request)
    {
        //dd($request);
        $store_id = auth()->user()->store->id;

        $value = $request->bot_settings;

        $bot_settings = StoreSettings::where([
            ['key', 'bot_settings'],
            ['store_id', $store_id],
        ])->first();

        if ($bot_settings != null) {

            // set payment method message if it's exist
            if (array_key_exists('payment_method', $bot_settings->value)) {
                $value['payment_method'] = $bot_settings->value['payment_method'];
            }

            $bot_settings->update(['value' => $value]);
        } else {
            $data = [
                "key" => "bot_settings",
                "value" => $value,
                "store_id" => $store_id,
            ];
            StoreSettings::create($data);
        }

        return redirect()->route('create_bot_settings')->with('success_message', __('app.responses_messages.settings_saved_successfully'));
    }

    public function work_hours()
    {
        $hours = StoreSettings::query()
        ->where([
            ['store_id' , auth()->user()->store->id],
            ['key' , 'work_hours']
            ])->first();

        $days = $hours->value ?? null;
        
        return view('newDashboard.work-hours', compact('days'));
    }

    public function storeWorkHours(Request $request)
    {
        $info = [];
        
        foreach ($request->all() as $name => $value)
        {
            $theDay = explode('_', $name);
            switch($theDay[1])
            {
                case 'Sat':
                    if ($theDay[0] == 'open')
                        $info['Sat']['open'] = $value;
                    else
                        $info['Sat']['close'] = $value;
                    break;
                case 'Sun':
                    if ($theDay[0] == 'open')
                        $info['Sun']['open'] = $value;
                    else
                        $info['Sun']['close'] = $value;
                    break;
                case 'Mon':
                    if ($theDay[0] == 'open')
                        $info['Mon']['open'] = $value;
                    else
                        $info['Mon']['close'] = $value;
                    break;
                case 'Tue':
                    if ($theDay[0] == 'open')
                        $info['Tue']['open'] = $value;
                    else
                        $info['Tue']['close'] = $value;
                    break;
                case 'Wed':
                    if ($theDay[0] == 'open')
                        $info['Wed']['open'] = $value;
                    else
                        $info['Wed']['close'] = $value;
                    break; 
                case 'Thu':
                    if ($theDay[0] == 'open')
                        $info['Thu']['open'] = $value;
                    else
                        $info['Thu']['close'] = $value;
                    break;
                case 'Fri':
                    if ($theDay[0] == 'open')
                        $info['Fri']['open'] = $value;
                    else
                        $info['Fri']['close'] = $value;
                    break; 
                default:
                    break;                                                                              
            }
        }

        //$arr = json_encode($info);

        $workHours = StoreSettings::query()
        ->where([
            ['store_id' , auth()->user()->store->id],
            ['key' , 'work_hours']
            ])->get();

        if($workHours != '[]')
        {
            StoreSettings::where([
                ['key', 'work_hours'],
                ['store_id', auth()->user()->store->id]
            ])
            ->update([
                'value' => $info,
                'updated_at' => now()
            ]);

        } else
        {
            $data = [
                'key' => 'work_hours',
                'value' => $info,
                'created_at' => now(),
                'store_id' => auth()->user()->store->id
            ];

            StoreSettings::create($data);
        }

        return redirect()->route('work_hours')->with('success_message', __('app.responses_messages.settings_saved_successfully'));

    }
  
    public function FAQ()
    {
        $QObjects = [];

        $questions = StoreSettings::where([
            ['key', 'FAQs'],
            ['store_id', auth()->user()->store->id],
        ])->first();

        if ($questions) {
            $QObjects = json_decode($questions->value);
        }

        return view('newDashboard.help.FAQ', compact('QObjects'));
    }
  
    public function store_question(Request $request)
    {
        $store_id = auth()->user()->store->id;

        $arr = [];
        $info = [];

        $questions = $request->questions;
        $answers = $request->answers;

        if ($questions != null) {
            for ($i = 0; $i < count($questions); $i++) {
                $info['question'] = $questions[$i];
                $info['answer'] = $answers[$i];
                $arr[] = $info;
            }
        }

        $arr = json_encode($arr);

        $store_questions = StoreSettings::where([
            ['key', 'FAQs'],
            ['store_id', $store_id],
        ])->first();

        if ($store_questions != null) {
            $store_questions->update(['value' => $arr]);
        } else {
            $data = [
                "key" => "FAQs",
                "value" => $arr,
                "store_id" => $store_id,
            ];
            StoreSettings::create($data);
        }
        updateMenus($store_id);
        return redirect()->route('FAQ')->with('success_message', __('app.responses_messages.saved_successfuly'));
    }

    public function contact_us()
    {
        $value = null;
        $store_id = auth()->user()->store->id;

        $contactUs = StoreSettings::where([
            ['key', 'Contact_Us'],
            ['store_id', $store_id],
        ])->first();

        if ($contactUs) {
            $value = json_decode($contactUs->value);
        }

        return view('newDashboard.help.contact-us', compact('value'));
    }

    public function store_contact_us(Request $request)
    {
        $store_id = auth()->user()->store->id;

        if (!$request->contact_us) {
            $info = "";
        } else {
            $info = $request->contact_us;
        }

        $value = json_encode($info);

        $contactUs = StoreSettings::where([
            ['key', 'Contact_Us'],
            ['store_id', $store_id],
        ])->first();

        if ($contactUs != null) {
            $contactUs->update(['value' => $value]);
        } else {
            $data = [
                "key" => "Contact_Us",
                "value" => $value,
                "store_id" => $store_id,
            ];

            StoreSettings::create($data);
        }
        updateMenus($store_id);
        return redirect()->route('contact_us')->with('success_message', __('app.responses_messages.saved_successfuly'));
    }

    public function about_us()
    {
        $value = null;
        $store_id = auth()->user()->store->id;

        $aboutUs = StoreSettings::where([
            ['key', 'About_Us'],
            ['store_id', $store_id],
        ])->first();

        if ($aboutUs) {
            $value = json_decode($aboutUs->value);
        }

        return view('newDashboard.help.about-us', compact('value'));
    }

    public function store_about_us(Request $request)
    {
        $store_id = auth()->user()->store->id;

        if (!$request->about_us) {
            $info = "";
        } else {
            $info = $request->about_us;
        }

        $value = json_encode($info);

        $aboutUs = StoreSettings::where([
            ['key', 'About_Us'],
            ['store_id', $store_id],
        ])->first();

        if ($aboutUs != null) {
            $aboutUs->update(['value' => $value]);
        } else {
            $data = [
                "key" => "About_Us",
                "value" => $value,
                "store_id" => $store_id,
            ];

            StoreSettings::create($data);
        }
        updateMenus($store_id);
        return redirect()->route('about_us')->with('success_message', __('app.responses_messages.saved_successfuly'));
    }

    public function createMySettings()
    {
        $customSettingsValue = [];
        $customSettings = StoreSettings::where([
            ['key', 'custom_settings'],
            ['store_id', auth()->user()->store_id],
        ])->first();

        if ($customSettings) {
            $customSettingsValue = $customSettings->value;
        }

        return view('newDashboard.products-settings', compact('customSettingsValue'));
    }

    public function storeMySettings(Request $request)
    {
        $this->validate($request, [
            'percentage_fees_amount' => 'required_if:fees_type,percentage',
            'number_fees_amount' => 'required_if:fees_type,number',
            'percentage_tax_amount' => 'required_if:tax_type,percentage',
            'number_tax_amount' => 'required_if:tax_type,number',
        ]);

        $store_id = auth()->user()->store_id;
        $value = [
            'fees_type' => $request->fees_type == 'noFees' ? '' : $request->fees_type,
            'fees_amount' => $request->fees_type == 'noFees' ? 0 : ($request->fees_type == 'percentage' ? $request->percentage_fees_amount : $request->number_fees_amount),
            'tax_type' => $request->tax_type == 'noTax' ? '' : $request->tax_type,
            'tax_amount' => $request->tax_type == 'noTax' ? 0 : ($request->tax_type == 'percentage' ? $request->percentage_tax_amount : $request->number_tax_amount),
        ];

        $customSettings = StoreSettings::where([
            ['key', 'custom_settings'],
            ['store_id', $store_id],
        ])->first();

        if ($customSettings) {
            $customSettings->update(['value' => $value]);
        } else {
            $data = [
                "key" => "custom_settings",
                "value" => $value,
                "store_id" => $store_id,
            ];

            StoreSettings::create($data);
        }

        return Redirect()->route('custom-settings.create')->with('success_message', __('app.responses_messages.updated_successfully'));
    }

    // get all stores
    public function index()
    {
        $stores = Store::select('id', 'name as store_name', 'is_active', DB::raw('Date(expires_at) as expires_at'))
            ->with('user')
            ->whereHas('user.roles', function ($q) {
                $q->where('name', 'store-owner');
            })->paginate(8);

        $stores_ids = $stores->pluck('id')->toArray();
        $storesWithServices = Store::select('stores.id as storeId', 'products.additional->permission_name as service')
            ->whereIn('stores.id', $stores_ids)
            ->join('orders', 'stores.order_id', 'orders.id')
            ->whereNull('orders.deleted_at')
            ->join('order_items', 'order_items.order_id', 'orders.id')
            ->whereNull('order_items.deleted_at')
            ->join('products', 'order_items.product_id', 'products.id')
            ->get();

        foreach ($stores as $store) {
            $services = $storesWithServices->where('storeId', $store->id)->pluck('service');
            $store['services'] = $services;
        }

        return view('newDashboard.stores.stores', compact('stores'));
    }

    public function edit_subscribtion_setting(Store $store)
    {
        $payment_method_message = '';

        $store = Store::select(
            'id',
            'name',
            'is_active',
            'order_id',
            DB::raw('Date(expires_at) as expires_at'),
            'created_at',
            'currency_status'
        )->where('id', $store->id)->first();

        $userInfo = $store->user()->select('email')->first();

        $order = Order::where('id', $store->order_id)->withTrashed()->first();
        $store['managerName'] = $order->first_name . ' ' . $order->last_name;
        $store['phoneNumber'] = $order->phone_number;
        $store['paymentMethod'] = $order->payment_method;

        $services = $store
            ->user
            ->first()
            ->permissions
            ->pluck('name')
            ->toArray();

        $main_features = Store::select('products.additional->permission_name as name')
            ->with('user')
            ->whereHas('user.roles', function ($q) {
                $q->where('name', 'super-admin');
            })
            ->join('products', 'products.store_id', 'stores.id')
            ->get();

        $bot_settings = StoreSettings::where([
            ['key', 'bot_settings'],
            ['store_id', $store->id],
        ])->first();

        if ($bot_settings && array_key_exists('payment_method', $bot_settings->value)) {
            $payment_method_message = $bot_settings->value['payment_method'];
        }

        return view('newDashboard.stores.edit', compact('store', 'services', 'main_features', 'payment_method_message', 'userInfo'));
    }

    public function update_subscribtion_setting(Store $store, Request $request)
    {
        $total = 0;
        $permissions = [];
        $status = $request->status ? 1 : 0;
        $currency_status = $request->currency_status ? 1 : 0;
        $user = User::where('store_id', $store->id)->first();

        if ($request->password) {
            $user = $store->user()->first();
            $user->update(['password' => Hash::make($request->password)]);
        }

        $data = [
            'name' => $request->storeName,
            'is_active' => $status,
            'expires_at' => $request->expires_at,
            'currency_status' => $currency_status,
        ];

        $store->update($data);

        $order = Order::where('id', $store->order_id)->first();
        $order->items()->delete();
        $maxDate = null;
        foreach ($request->features ?? [] as $feature) {
            $product = Product::whereJsonContains('additional->permission_name', $feature)->first();
            $permission_name = $product->additional['permission_name'];
            $permission = Permission::findByName($permission_name);

            $expires_at = $request->feature_expires_at[$permission_name] ?? now()->addMonth();
            $expires_at = Carbon::parse($expires_at);

            if ($maxDate == null) $maxDate = $expires_at;

            $maxDate = $expires_at->gt($maxDate) ? $expires_at : $maxDate;

            $permissions[$permission->id] = [
                'expires_at' => Carbon::parse($expires_at),
            ];

            $total += $product->price;

            OrderItem::query()->create([
                'product_id' => $product->id,
                'order_id' => $store->order_id,
                'total' => $product->price,
                'quantity' => 1,
                'additional' => '',
            ]);
        }

        // couldn't set permission expiry date by calling syncPermissions
        $user->permissions()->sync($permissions);

        $order->update(['total' => $total]);

        $maxDate = $request->expires_at ? Carbon::parse($request->expires_at) : $maxDate;

        $store->update([
            'expires_at' => $maxDate
        ]);

        // handle payment method message
        $bot_settings = StoreSettings::where([
            ['key', 'bot_settings'],
            ['store_id', $store->id],
        ])->first();

        if ($bot_settings) {
            $settings = $bot_settings->value;
            $settings['payment_method'] = $request->payment_method_message;
            $bot_settings->update(['value' => $settings]);
        } else {
            $data = [
                "key" => "bot_settings",
                "value" => [
                    'steps' => [
                        'governorate' => 'enabled',
                        'address' => 'enabled',
                        'notes' => 'enabled',
                    ],
                    'hour' => 'enabled',
                    'payment_method' => $request->payment_method_message,
                ],
                "store_id" => $store->id,
            ];
            StoreSettings::create($data);
        }

        return redirect()->route('stores.index')->with('success_message', __('app.responses_messages.updated_successfully'));
    }

    public function resetStore(Store $store)
    {
        $carts = Cart::where('store_id', $store->id);
        $carts_ids = $carts->pluck('id')->toArray();
        CartItem::whereIn('cart_id', $carts_ids)->delete();
        $carts->delete();

        $orders = Order::where('store_id', $store->id);
        $orders_ids = $orders->pluck('id')->toArray();
        OrderItem::whereIn('order_id', $orders_ids)->delete();
        $orders->delete();

        Product::where('store_id', $store->id)->delete();
        Category::where('store_id', $store->id)->delete();
        return redirect()->route('stores.index')->with('success_message', __('app.responses_messages.deleted_successfully'));
    }

    public function checkStoreSlug()
    {
        $slug = request()->get('slug');

        $user = current_user();

        if (!$user)
            abort(403);

        $storeExist = Store::query()
            ->where('slug', $slug)
            ->whereNot('id', $user->store_id)
            ->exists();
        $message = $storeExist ? __('app.slug_available') : __('app.slug_not_available');

        return response()->json([
            'valid' => !$storeExist,
            'message' => $message,
        ]);
    }
}
