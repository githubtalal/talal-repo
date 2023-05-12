<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function login(Request $request): \Illuminate\Http\RedirectResponse
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
        ]);
        if (!auth('web')->attempt($request->only('email', 'password'),$request->only('remember'))) {
            return back()->with('status', 'Email or password is invalid');
        }
        $store = auth('web')->user()->store;
        $message = null;

        if (auth()->user()->hasRole('super-admin'))
            return redirect()->route('dashboard');

        if (!($store->is_active ?? false)) {
            $message = __('subscription.inactive_store');
        }
        if (now()->gt($store->expires_at)) {
            $message = __('subscription.expired_store');
        }

        if ($message) {
            auth('web')->logout();
            return redirect('/')->with('error_message', $message);
        }

        return redirect()->route('dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function create()
    {

    }
}
