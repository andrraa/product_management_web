<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use App\Http\Requests\ProfilePasswordRequest;
use App\Http\Requests\ProfileRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        $validatorProfile = Helper::generateValidator(
            ProfileRequest::class,
            '#form-profile'
        );
        $validatorPassword = Helper::generateValidator(
            ProfilePasswordRequest::class,
            '#form-profile-password'
        );

        return view('profile.index', compact(['validatorProfile', 'validatorPassword', 'user']));
    }

    public function store(ProfileRequest $request): RedirectResponse
    {   
        $user = Auth::user();

        $user->update($request->validated());

        return to_route('profile.index')->with('success', 'Profile updated successfully!');
    }

    public function password(ProfilePasswordRequest $request): RedirectResponse
    {
        $user = Auth::user();

        $password = Hash::make($request->validated()['password']);

        $user->update([
            'password' => $password
        ]);

        return to_route('profile.index')->with('success', 'Password updated successfully!');
    }
}
