<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function show(Request $request)
    {
        if ($request->session()->has('user_id')) {
            return redirect('/');
        }

        if ($request->has('redirect')) {
            $request->session()->put('redirect_after_login', $request->get('redirect'));
        }

        return view('login');
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'phone' => ['required', 'regex:/^01[3-9]\d{8}$/'],
            'password' => ['required', 'string'],
        ], [
            'phone.regex' => 'Please enter a valid Bangladeshi phone number (11 digits starting with 01).',
        ]);

        $user = User::where('phone', trim($validated['phone']))->first();

        if (! $user || ! Hash::check($validated['password'], $user->password_hash)) {
            return back()->withInput()->withErrors([
                'phone' => 'Invalid phone number or password.',
            ]);
        }

        if (! $user->verified) {
            $request->session()->put('verify_email', $user->email);

            return redirect()->route('register.verify');
        }

        if ($user->status !== 'active') {
            return back()->withInput()->withErrors([
                'phone' => 'Your account is currently ' . $user->status . '. Please contact support.',
            ]);
        }

        $request->session()->put([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_role' => $user->role,
            'user_hall' => $user->hall,
            'login_time' => time(),
        ]);
        $request->session()->regenerate();

        $user->last_login = now();
        $user->save();

        $redirectTarget = $request->session()->pull('redirect_after_login', '/');
        $response = redirect()->to($redirectTarget);

        if ($request->boolean('remember_me')) {
            $token = Str::random(40);
            $response->withCookie(cookie('remember_token', $user->id . ':' . $token, 60 * 24 * 30));
        } else {
            $response->withCookie(cookie()->forget('remember_token'));
        }

        return $response;
    }

    public function logout(Request $request)
    {
        $request->session()->forget(['user_id', 'user_name', 'user_role', 'user_hall', 'login_time']);
        $request->session()->regenerateToken();

        return redirect()->route('login')->withCookie(cookie()->forget('remember_token'));
    }
}
