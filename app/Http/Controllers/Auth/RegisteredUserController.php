<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
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
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            // Pastikan password ini sesuai (pakai confirmed kalau lo pake input konfirmasi di form)
            'password' => ['required', 'confirmed', 'min:8'], 
            'role' => ['required', 'in:user,publisher'],
            'nama_publisher' => ['required_if:role,publisher', 'nullable', 'string', 'max:255'],
        ], [
            'nama_publisher.required_if' => 'Nama publisher wajib diisi jika memilih publisher.',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            // INI YANG PALING PENTING BIAR MASUK KE DATABASE
            'role' => $request->role,
            'nama_publisher' => $request->role === 'publisher' ? $request->nama_publisher : null,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
