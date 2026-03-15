<?php

namespace App\Http\Controllers\Back\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Back\Admin\StoreUserRequest;
use App\Http\Requests\Back\Admin\UpdateUserRequest;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();

        $data = [
            'users' => $users,
            'totalUsers' => $users->count(),
            'totalUsersLastMonthPercentage' => $users->count() > 0
              ? round(($users->where('created_at', '>=', now()->subMonth())->count() / $users->count()) * 100, 2)
              : 0,
            'adminUsers' => $users->where('role', 'admin')->count(),
            'activeUsers' => $users->where('is_active', true)->count(),
            'inactiveUsers' => $users->where('is_active', false)->count(),
        ];

        return view('content.back.admin.user.index', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $validatedData = $request->validated();
        $validatedData['is_active'] = $request->boolean('is_active');

        User::create($validatedData);

        return redirect()->route('admin.users.index')->with('success', 'User added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::findOrFail($id);

        return view('content.back.admin.user.show', ['user' => $user]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);

        return view('content.back.admin.user.edit', ['user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, string $id)
    {
        $user = User::findOrFail($id);
        $validatedData = $request->validated();
        $validatedData['is_active'] = $request->boolean('is_active');

        if (empty($validatedData['password'])) {
            unset($validatedData['password']);
        }

        $user->update($validatedData);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }
}
