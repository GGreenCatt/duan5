<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->merge([
            'name' => strip_tags($request->name),
            'email' => strip_tags($request->email),
        ]);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                'unique:' . User::class,
                'regex:/^[a-zA-Z0-9.]+@(gmail\.com|edu\.com|yahoo\.com|outlook\.com)$/'
            ],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'email.regex' => 'Email chỉ được chứa các ký tự a-z, 0-9, dấu chấm (.) và phải kết thúc bằng một trong các đuôi: @gmail.com, @edu.com, @yahoo.com, @outlook.com.',
        ]);


        $user = User::create([
            'name' => htmlspecialchars($request->name, ENT_QUOTES, 'UTF-8'),
            'email' => htmlspecialchars($request->email, ENT_QUOTES, 'UTF-8'),
            'password' => Hash::make($request->password),
            'role' => 'User', // Auto set role as User
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
