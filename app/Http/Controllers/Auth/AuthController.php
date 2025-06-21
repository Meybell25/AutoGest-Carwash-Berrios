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
use Illuminate\Support\Facades\Cache;

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
     * Mostrar formulario de registro
     */
    public function showRegister(): View
    {
        return view('auth.register');
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

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            if (!$user->estado) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Tu cuenta está desactivada. Contacta al administrador.',
                ])->onlyInput('email');
            }

            return $this->redirectByRole($user);
        }

        return back()->withErrors([
            'email' => 'Las credenciales no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

    /**
     * Procesar registro
     */


    public function register(Request $request): RedirectResponse
    {
        $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:usuarios'],
            'telefono' => ['required', 'string', 'digits:8', 'unique:usuarios'],
            'password' => ['required', 'confirmed', Rules\Password::min(8)->mixedCase()->numbers()->symbols()],
        ], [
            'telefono.required' => 'El teléfono es requerido',
            'telefono.digits' => 'El teléfono debe tener exactamente 8 dígitos',
            'telefono.unique' => 'Este teléfono ya está registrado',
            'password.mixed' => 'La contraseña debe contener al menos una letra mayúscula y una minúscula.',
            'password.numbers' => 'La contraseña debe contener al menos un número.',
            'password.symbols' => 'La contraseña debe contener al menos un carácter especial (!@#$%^&*).',
            'password.confirmed' => 'Las contraseñas no coinciden.'
        ]);

        $user = Usuario::create([
            'nombre' => $request->nombre,
            'email' => $request->email,
            'telefono' => $request->telefono,
            'password' => Hash::make($request->password),
            'rol' => Usuario::ROL_CLIENTE,
            'estado' => true,
        ]);

        // Limpiar caché de estadísticas
        Cache::forget('dashboard_stats');

        event(new Registered($user));
        Auth::login($user);

        return redirect()->route('cliente.dashboard')
            ->with('success', '¡Registro exitoso! Bienvenido a nuestro sistema.');
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
