<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Participante;
use App\Models\Role;

class RegisterController extends Controller
{
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
            'nombre' => 'required|string|max:255',
            'no_control' => 'required|string|max:20|unique:participante,No_Control',
            'carrera_id' => 'required|integer|exists:carrera,Id',
            'correo' => 'required|email|max:255|unique:users,email',
            'telefono' => 'required|string|max:15',
            'terms' => 'required|accepted',
        ]);

        DB::beginTransaction();

        try {
            // 1. Crear usuario en tabla users
            $user = User::create([
                'name' => $request->username,
                'email' => $request->correo,
                'password' => $request->password, // Se hashea automáticamente
                'is_active' => true,
            ]);

            // 2. Crear participante
            Participante::create([
                'user_id' => $user->id, // ✅ Usar user_id
                'No_Control' => $request->no_control,
                'Carrera_id' => $request->carrera_id,
                'Nombre' => $request->nombre,
                'Correo' => $request->correo,
                'telefono' => $request->telefono,
            ]);

            // 3. Asignar rol de Participante
            $roleParticipante = Role::where('Nombre', 'Participante')->first();
            if ($roleParticipante) {
                DB::table('usuario_rol')->insert([
                    'user_id' => $user->id, // ✅ Usar user_id
                    'Id_Rol' => $roleParticipante->Id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::commit();

            return redirect()->route('login')->with('success', '¡Registro exitoso! Ahora puedes iniciar sesión.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al registrar usuario: ' . $e->getMessage()])->withInput();
        }
    }
}
