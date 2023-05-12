<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index()
    {
//        $user = User::paginate(10);
//        return view('admin.admins-table', ['user'=> $user]);
    }

    public function profile(User $user, Request $request)
    {
        return view('admin.admin-profile', ['user' => $user]);
    }

    public function changePassword(User $user, Request $request)
    {
        $this->validate($request, [
            'current_password' => 'required_with:new_password',
            'new_password' => 'required_with:current_password|confirmed',
        ]);
        if ($request->has('password') && !Hash::check($request->get('current_password'), $user->password)) {
            return back()->with('status', 'Wrong information');
        }
        $data = $request->all();
        if (!$request->new_password == null) {
            $user['password'] = Hash::make($data['new_password']);
        }
        $user->update($data);
        return redirect()->route('dashboard');
    }

    public function edit(User $user, Request $request)
    {
        $govers = [];
        foreach (DB::table('city')->get() as $gov) {
            $govers[$gov->id] = [
                'id' => $gov->id,
                'name' => $gov->name,
                'deps' => DB::table('district')->where('city_id', $gov->id)->get()
            ];
        }
        return view('admin.edit-admin', ['user' => $user, 'govers' => $govers]);
    }

    public function update(User $user, Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
            'governorate' => 'nullable',
            'role' => 'required',
            'active' => 'required',
        ]);
        $data = $request->all();
        $user->update($data);
        return redirect()->route('users');
    }

    public function create()
    {
        return view('register');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|unique:users,email',
            'name' => 'required',
            'phone_number' => 'required',
            'password' => 'required',
            'location' => 'nullable',
            'role' => 'nullable',
        ]);

        $store = Store::create([
            'button_text' => 'Pay Now',
            'token' => Str::random(24),
        ]);
        $data = $request->all();
        $data['password'] = Hash::make($data['password']);
        $data['store_id'] = $store->id;
        $user = User::create($data);

        Auth::loginUsingId($user->id);
        return redirect()->route('dashboard');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users');
    }

    public function updatePassword(User $user, Request $request): \Illuminate\Http\RedirectResponse
    {
        $this->validate($request, [
            'current_password' => 'required_with:new_password',
            'new_password' => 'required_with:current_password|confirmed',
            // new_password_confirmation

        ]);
        if (!Hash::check($request->get('current_password'), $user->password)) {
            return back()->with('status', 'Wrong information');
        }
        $data = $request->all();
        $data['password'] = Hash::make($data['password']);
        $user->save($data);
        return redirect()->route('dashboard');
    }
}
