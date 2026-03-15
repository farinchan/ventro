<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function index(): View
    {
        $pageConfigs = ['myLayout' => 'blank'];

        return view('content.auth.register', ['pageConfigs' => $pageConfigs]);
    }

    public function store(RegisterRequest $request): RedirectResponse
    {
        $username = $request->string('username')->toString();

        $user = User::query()->create([
            'username' => $username,
            'name' => $username,
            'email' => $request->string('email')->toString(),
            'password' => $request->string('password')->toString(),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('pages-home');
    }
}
