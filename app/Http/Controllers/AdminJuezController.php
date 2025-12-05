<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Juez;
use App\Models\User;
use App\Models\Especialidad;
use App\Models\Evento;

class AdminJuezController extends Controller
{
    /**
     * Mostrar lista de jueces (ya existe en JuezController pero para admin)
     */
    public function index()
    {
        $jueces = Juez::with(['user', 'especialidad', 'eventos'])->get();

        return view('admin.jueces.list', compact('jueces'));
    }

    /**
     * Mostrar formulario de creación de juez
     */
    public function create()
    {
        $especialidades = Especialidad::all();
        $eventos = Evento::where('Estado', 'Activo')->get();

        return view('admin.jueces.create', compact('especialidades', 'eventos'));
    }

    /**
     * Guardar nuevo juez
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'telefono' => 'nullable|string|max:20',
            'especialidad_id' => 'required|exists:especialidad,Id',
            'eventos' => 'nullable|array',
            'eventos.*' => 'exists:evento,Id',
        ], [
            'nombre.required' => 'El nombre es obligatorio',
            'email.required' => 'El correo electrónico es obligatorio',
            'email.email' => 'El correo electrónico debe ser válido',
            'email.unique' => 'Este correo electrónico ya está registrado',
            'password.required' => 'La contraseña es obligatoria',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres',
            'password.confirmed' => 'Las contraseñas no coinciden',
            'especialidad_id.required' => 'Debes seleccionar una especialidad',
            'especialidad_id.exists' => 'La especialidad seleccionada no es válida',
        ]);

        DB::beginTransaction();

        try {
            // Crear usuario
            $user = User::create([
                'name' => $validated['nombre'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            // Asignar rol de Juez
            $rolJuez = DB::table('rol')->where('Nombre', 'Juez')->first();

            if (!$rolJuez) {
                throw new \Exception('El rol "Juez" no existe en el sistema. Ejecuta los seeders primero.');
            }

            DB::table('usuario_rol')->insert([
                'user_id' => $user->id,
                'Id_Rol' => $rolJuez->Id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Crear registro de juez
            $juez = Juez::create([
                'user_id' => $user->id,
                'Nombre' => $validated['nombre'],
                'Correo' => $validated['email'],
                'telefono' => $validated['telefono'] ?? null,
                'Id_especialidad' => $validated['especialidad_id'],
            ]);

            // Asignar eventos si se seleccionaron
            if (!empty($validated['eventos'])) {
                foreach ($validated['eventos'] as $eventoId) {
                    DB::table('evento_juez')->insert([
                        'Evento_id' => $eventoId,
                        'Juez_id' => $juez->Id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('admin.jueces.list')
                ->with('success', '¡Juez registrado exitosamente! Se han enviado las credenciales a ' . $validated['email']);

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Error al registrar el juez: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Mostrar detalles de un juez
     */
    public function show($id)
    {
        $juez = Juez::with([
            'user',
            'especialidad',
            'eventos',
            'calificaciones.criterio',
            'calificaciones.proyecto.equipo'
        ])->findOrFail($id);

        return view('admin.jueces.show', compact('juez'));
    }

    /**
     * Mostrar formulario de edición de juez
     */
    public function edit($id)
    {
        $juez = Juez::with(['user', 'especialidad', 'eventos'])->findOrFail($id);
        $especialidades = Especialidad::all();
        $eventos = Evento::all();

        return view('admin.jueces.edit', compact('juez', 'especialidades', 'eventos'));
    }

    /**
     * Actualizar juez
     */
    public function update(Request $request, $id)
    {
        $juez = Juez::findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $juez->user_id,
            'password' => 'nullable|string|min:8|confirmed',
            'telefono' => 'nullable|string|max:20',
            'especialidad_id' => 'required|exists:especialidad,Id',
            'eventos' => 'nullable|array',
            'eventos.*' => 'exists:evento,Id',
        ], [
            'nombre.required' => 'El nombre es obligatorio',
            'email.required' => 'El correo electrónico es obligatorio',
            'email.email' => 'El correo electrónico debe ser válido',
            'email.unique' => 'Este correo electrónico ya está registrado',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres',
            'password.confirmed' => 'Las contraseñas no coinciden',
            'especialidad_id.required' => 'Debes seleccionar una especialidad',
            'especialidad_id.exists' => 'La especialidad seleccionada no es válida',
        ]);

        DB::beginTransaction();

        try {
            // Actualizar usuario
            $user = $juez->user;
            $user->name = $validated['nombre'];
            $user->email = $validated['email'];

            // Solo actualizar contraseña si se proporcionó una nueva
            if (!empty($validated['password'])) {
                $user->password = Hash::make($validated['password']);
            }

            $user->save();

            // Actualizar juez
            $juez->Nombre = $validated['nombre'];
            $juez->Correo = $validated['email'];
            $juez->telefono = $validated['telefono'] ?? null;
            $juez->Id_especialidad = $validated['especialidad_id'];
            $juez->save();

            // Actualizar eventos asignados
            // Primero eliminar todas las asignaciones actuales
            DB::table('evento_juez')->where('Juez_id', $juez->Id)->delete();

            // Luego agregar las nuevas asignaciones
            if (!empty($validated['eventos'])) {
                foreach ($validated['eventos'] as $eventoId) {
                    DB::table('evento_juez')->insert([
                        'Evento_id' => $eventoId,
                        'Juez_id' => $juez->Id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('admin.jueces.show', $juez->Id)
                ->with('success', '¡Juez actualizado exitosamente!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->with('error', 'Error al actualizar el juez: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Eliminar juez
     */
    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $juez = Juez::findOrFail($id);
            $user = $juez->user;

            // Eliminar registro de juez (las relaciones se eliminarán en cascada)
            $juez->delete();

            // Eliminar usuario
            if ($user) {
                $user->delete();
            }

            DB::commit();

            return redirect()->route('admin.jueces.list')
                ->with('success', 'Juez eliminado exitosamente');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Error al eliminar el juez: ' . $e->getMessage());
        }
    }
}
