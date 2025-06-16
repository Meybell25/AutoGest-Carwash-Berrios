<?php
// app/Http/Controllers/PerfilController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PerfilController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        return view('perfil.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
    
        $request->validate([
            'nombre' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:20',
        ]);

        $user->update([
            'nombre' => $request->nombre,
            'telefono' => $request->telefono,
        ]);

        return back()->with('success', 'Perfil actualizado correctamente');
    }

    public function configuracion()
    {
        return view('configuracion', ['user' => Auth::user()]);
    }

    public function updateEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|max:255|unique:usuarios,email,'.Auth::id(),
        ]);

        Auth::user()->update(['email' => $request->email]);

        return back()->with('success', 'Email actualizado correctamente');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return back()->withErrors(['current_password' => 'La contraseña actual no es correcta']);
        }

        Auth::user()->update(['password' => Hash::make($request->password)]);

        return back()->with('success', 'Contraseña actualizada correctamente');
    }
}