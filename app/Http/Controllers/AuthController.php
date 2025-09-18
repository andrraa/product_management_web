<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function index(): View
    {
        $validator = Helper::generateValidator(LoginRequest::class, '#form-login');

        return view('auth.login', compact('validator'));
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $user = User::firstWhere('username', $validated['username']);

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return to_route('login')
                ->with('error', 'Invalid username or password');
        }

        Auth::login($user);

        return to_route('dashboard');
    }

    public function logout(Request $request): RedirectResponse
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        Auth::logout();

        return to_route('login');
    }
}
