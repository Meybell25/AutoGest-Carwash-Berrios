<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Mostrar formulario de login
     */
    public function showLogin(): View
    {
        return view('auth.login');
    }

    /**
     * Procesar login
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::guard('web')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();
            
            // Verificar si el usuario está activo
            if (!$user->estado) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Tu cuenta está desactivada. Contacta al administrador.',
                ]);
            }

            // Redireccionar según el rol
            return $this->redirectByRole($user);
        }

        return back()->withErrors([
            'email' => 'Las credenciales no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

    /**
     * Mostrar formulario de registro
     */
    public function showRegister(): View
    {
        return view('auth.register');
    }

    /**
     * Procesar registro
     */
    public function register(Request $request): RedirectResponse
    {
        $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:usuarios'],
            'telefono' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = Usuario::create([
            'nombre' => $request->nombre,
            'email' => $request->email,
            'telefono' => $request->telefono,
            'password' => Hash::make($request->password),
            'rol' => Usuario::ROL_CLIENTE, // Por defecto cliente
            'estado' => true,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('cliente.dashboard');
    }

    /**
     * Logout
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Redireccionar según el rol del usuario
     */
    private function redirectByRole($user): RedirectResponse
    {
        switch ($user->rol) {
            case Usuario::ROL_ADMIN:
                return redirect()->route('admin.dashboard');
            case Usuario::ROL_EMPLEADO:
                return redirect()->route('empleado.dashboard');
            case Usuario::ROL_CLIENTE:
                return redirect()->route('cliente.dashboard');
            default:
                return redirect()->route('dashboard');
        }
    }
}