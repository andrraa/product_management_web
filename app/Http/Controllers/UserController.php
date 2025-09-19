<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use App\Http\Requests\UserPassworRequest;
use App\Http\Requests\UserRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::query()
            ->select([
                'user_id',
                'name',
                'username',
                'role',
                'shift'
            ])
            ->get();

        return view('user.index', compact('users'));
    }

    public function create(): View
    {
        $validator = Helper::generateValidator(UserRequest::class, '#form-user');

        $state = 'create';
        
        return view('user.create', compact(['validator', 'state']));
    }

    public function store(UserRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        if (User::create($validated)) {
            return to_route('user.index')->with('success', 'User created successfully!');
        }

        return to_route('user.index')->with('error', 'Failed to create user!');
    }

    public function show(User $user): View
    {
        return view('user.show', compact('user'));
    }

    public function edit(User $user): View
    {
        $validator = Helper::generateValidator(UserUpdateRequest::class, '#form-user');

        $state = 'edit';

        return view('user.edit', compact(['user', 'validator', 'state']));
    }

    public function update(UserUpdateRequest $request, User $user): RedirectResponse
    {
        $validated = $request->validated();

        if ($user->update($validated)) {
            return to_route('user.index')->with('success', 'User updated successfully!');
        }

        return to_route('user.index')->with('error', 'Failed to update user!');
    }

    public function destroy(Request $request, User $user): JsonResponse
    {
        if (!$request->expectsJson()) {
            abort(403);
        }

        $user->delete();

        return response()->json(['success' => true]);
    }

    public function editPassword(User $user): View
    {
        $validator = Helper::generateValidator(UserPassworRequest::class, '#form-user');

        return view('user.password', compact(['user', 'validator']));
    }

    public function updatePassword(UserPassworRequest $request, User $user)
    {

    }
}
