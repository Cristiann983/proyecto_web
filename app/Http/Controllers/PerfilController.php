<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class PerfilController extends Controller
{
    /**
     * Mostrar la vista del perfil del usuario
     */
    public function show()
    {
        $user = Auth::user();
        $participante = $user->participante;
        $juez = $user->juez;

        return view('perfil.show', compact('user', 'participante', 'juez'));
    }

    /**
     * Mostrar la vista de edición del perfil
     */
    public function edit()
    {
        $user = Auth::user();
        $participante = $user->participante;
        $juez = $user->juez;

        return view('perfil.edit', compact('user', 'participante', 'juez'));
    }

    /**
     * Actualizar los datos del perfil
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        $participante = $user->participante;
        $juez = $user->juez;

        // Validar los datos
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'telefono' => 'nullable|string|max:15',
            'password' => 'nullable|min:8|confirmed',
        ], [
            'name.required' => 'El nombre es obligatorio',
            'email.required' => 'El correo es obligatorio',
            'email.email' => 'El correo debe ser válido',
            'email.unique' => 'Este correo ya está en uso',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres',
            'password.confirmed' => 'Las contraseñas no coinciden',
        ]);

        // Actualizar usuario
        $user->name = $request->name;
        $user->email = $request->email;

        // Solo actualizar contraseña si se proporcionó una nueva
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        // Actualizar participante si existe
        if ($participante) {
            $participante->Nombre = $request->name;
            $participante->Correo = $request->email;

            if ($request->filled('telefono')) {
                $participante->telefono = $request->telefono;
            }

            $participante->save();
        }

        // Actualizar juez si existe
        if ($juez) {
            $juez->Nombre = $request->name;
            $juez->Correo = $request->email;

            if ($request->filled('telefono')) {
                $juez->telefono = $request->telefono;
            }

            $juez->save();
        }

        return redirect()->route('perfil.show')->with('success', 'Perfil actualizado exitosamente');
    }
}
