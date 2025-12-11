<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Role;
use App\Models\Participante;
use App\Models\Juez;
use App\Models\Carrera;
use App\Models\Especialidad;

class AdminUsuarioController extends Controller
{
    /**
     * Mostrar lista de usuarios
     */
    public function index()
    {
        $user = Auth::user();
        
        if (!$user->hasRole('Administrador')) {
            return redirect()->route('dashboard')
                ->with('error', 'No tienes permisos para acceder a esta sección.');
        }

        $usuarios = User::with(['roles', 'participante.carrera', 'juez.especialidad'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('admin.usuarios.index', compact('usuarios'));
    }

    /**
     * Mostrar formulario de creación de usuario
     */
    public function create()
    {
        $user = Auth::user();
        
        if (!$user->hasRole('Administrador')) {
            return redirect()->route('dashboard')
                ->with('error', 'No tienes permisos para acceder a esta sección.');
        }

        $roles = Role::all();
        $carreras = Carrera::all();
        $especialidades = Especialidad::all();
        
        return view('admin.usuarios.create', compact('roles', 'carreras', 'especialidades'));
    }

    /**
     * Guardar nuevo usuario
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'rol_id' => 'required|exists:rol,Id',
            // is_active no necesita validación - se maneja con has()
            // Campos específicos para participante
            'no_control' => 'nullable|string|max:50',
            'carrera_id' => 'nullable|exists:carrera,Id',
            'telefono' => 'nullable|string|max:20',
            // Campos específicos para juez
            'especialidad_id' => 'nullable|exists:especialidad,Id',
        ], [
            'nombre.required' => 'El nombre es obligatorio',
            'email.required' => 'El correo electrónico es obligatorio',
            'email.email' => 'El correo electrónico debe ser válido',
            'email.unique' => 'Este correo electrónico ya está registrado',
            'password.required' => 'La contraseña es obligatoria',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres',
            'password.confirmed' => 'Las contraseñas no coinciden',
            'rol_id.required' => 'Debes seleccionar un rol',
            'rol_id.exists' => 'El rol seleccionado no es válido',
        ]);

        DB::beginTransaction();

        try {
            // Crear usuario
            $user = User::create([
                'name' => $validated['nombre'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'is_active' => $request->has('is_active') ? true : false,
            ]);

            // Asignar rol
            DB::table('usuario_rol')->insert([
                'user_id' => $user->id,
                'Id_Rol' => $validated['rol_id'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Obtener el nombre del rol
            $rol = Role::find($validated['rol_id']);

            // Crear registro adicional según el rol
            if ($rol->Nombre === 'Participante') {
                Participante::create([
                    'user_id' => $user->id,
                    'No_Control' => $validated['no_control'],
                    'Carrera_id' => $validated['carrera_id'],
                    'Nombre' => $validated['nombre'],
                    'Correo' => $validated['email'],
                    'telefono' => $validated['telefono'] ?? null,
                ]);
            } elseif ($rol->Nombre === 'Juez') {
                Juez::create([
                    'user_id' => $user->id,
                    'Nombre' => $validated['nombre'],
                    'Correo' => $validated['email'],
                    'telefono' => $validated['telefono'] ?? null,
                    'Id_especialidad' => $validated['especialidad_id'],
                ]);
            }

            DB::commit();

            return redirect()->route('admin.usuarios.index')
                ->with('success', '¡Usuario creado exitosamente!');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Error al crear el usuario: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Mostrar detalles de un usuario
     */
    public function show($id)
    {
        $user = Auth::user();
        
        if (!$user->hasRole('Administrador')) {
            return redirect()->route('dashboard')
                ->with('error', 'No tienes permisos para acceder a esta sección.');
        }

        $usuario = User::with([
            'roles',
            'participante.carrera',
            'participante.equipos',
            'juez.especialidad',
            'juez.eventos'
        ])->findOrFail($id);

        return view('admin.usuarios.show', compact('usuario'));
    }

    /**
     * Mostrar formulario de edición de usuario
     */
    public function edit($id)
    {
        $user = Auth::user();
        
        if (!$user->hasRole('Administrador')) {
            return redirect()->route('dashboard')
                ->with('error', 'No tienes permisos para acceder a esta sección.');
        }

        $usuario = User::with(['roles', 'participante', 'juez'])->findOrFail($id);
        $roles = Role::all();
        $carreras = Carrera::all();
        $especialidades = Especialidad::all();

        return view('admin.usuarios.edit', compact('usuario', 'roles', 'carreras', 'especialidades'));
    }

    /**
     * Actualizar usuario
     */
    public function update(Request $request, $id)
    {
        $usuario = User::findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $usuario->id,
            'password' => 'nullable|string|min:8|confirmed',
            'rol_id' => 'required|exists:rol,Id',
            // is_active no necesita validación - se maneja con has()
            // Campos específicos para participante
            'no_control' => 'nullable|string|max:50',
            'carrera_id' => 'nullable|exists:carrera,Id',
            'telefono' => 'required|string|max:20',
            // Campos específicos para juez
            'especialidad_id' => 'nullable|exists:especialidad,Id',
        ], [
            'nombre.required' => 'El nombre es obligatorio',
            'email.required' => 'El correo electrónico es obligatorio',
            'email.email' => 'El correo electrónico debe ser válido',
            'email.unique' => 'Este correo electrónico ya está registrado',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres',
            'password.confirmed' => 'Las contraseñas no coinciden',
            'rol_id.required' => 'Debes seleccionar un rol',
            'telefono.required' => 'El teléfono es obligatorio',
            'rol_id.exists' => 'El rol seleccionado no es válido',
        ]);

        DB::beginTransaction();

        try {
            // Actualizar usuario
            $usuario->name = $validated['nombre'];
            $usuario->email = $validated['email'];
            $usuario->is_active = $request->has('is_active') ? true : false;

            // Solo actualizar contraseña si se proporcionó una nueva
            if (!empty($validated['password'])) {
                $usuario->password = Hash::make($validated['password']);
            }

            $usuario->save();

            // Actualizar rol (eliminar rol anterior y asignar nuevo)
            DB::table('usuario_rol')->where('user_id', $usuario->id)->delete();
            DB::table('usuario_rol')->insert([
                'user_id' => $usuario->id,
                'Id_Rol' => $validated['rol_id'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Obtener el nombre del rol
            $rol = Role::find($validated['rol_id']);

            // Actualizar o crear registro adicional según el rol
            if ($rol->Nombre === 'Participante') {
                $participante = $usuario->participante;
                if ($participante) {
                    $participante->update([
                        'No_Control' => $validated['no_control'],
                        'Carrera_id' => $validated['carrera_id'],
                        'Nombre' => $validated['nombre'],
                        'Correo' => $validated['email'],
                        'telefono' => $validated['telefono'] ?? null,
                    ]);
                } else {
                    Participante::create([
                        'user_id' => $usuario->id,
                        'No_Control' => $validated['no_control'],
                        'Carrera_id' => $validated['carrera_id'],
                        'Nombre' => $validated['nombre'],
                        'Correo' => $validated['email'],
                        'telefono' => $validated['telefono'] ?? null,
                    ]);
                }
                // Eliminar registro de juez si existe
                if ($usuario->juez) {
                    $usuario->juez->delete();
                }
            } elseif ($rol->Nombre === 'Juez') {
                $juez = $usuario->juez;
                if ($juez) {
                    $juez->update([
                        'Nombre' => $validated['nombre'],
                        'Correo' => $validated['email'],
                        'telefono' => $validated['telefono'] ?? null,
                        'Id_especialidad' => $validated['especialidad_id'],
                    ]);
                } else {
                    Juez::create([
                        'user_id' => $usuario->id,
                        'Nombre' => $validated['nombre'],
                        'Correo' => $validated['email'],
                        'telefono' => $validated['telefono'] ?? null,
                        'Id_especialidad' => $validated['especialidad_id'],
                    ]);
                }
                // Eliminar registro de participante si existe
                if ($usuario->participante) {
                    $usuario->participante->delete();
                }
            } else {
                // Si es administrador, eliminar ambos registros si existen
                if ($usuario->participante) {
                    $usuario->participante->delete();
                }
                if ($usuario->juez) {
                    $usuario->juez->delete();
                }
            }

            DB::commit();

            return redirect()->route('admin.usuarios.index')
                ->with('success', 'Rol del usuario "' . $usuario->name . '" actualizado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->with('error', 'Error al actualizar el usuario: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Eliminar usuario
     */
    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $usuario = User::findOrFail($id);

            // Eliminar registros relacionados
            if ($usuario->participante) {
                $usuario->participante->delete();
            }
            if ($usuario->juez) {
                $usuario->juez->delete();
            }

            // Eliminar usuario
            $usuario->delete();

            DB::commit();

            return redirect()->route('admin.usuarios.index')
                ->with('success', 'Usuario eliminado exitosamente');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Error al eliminar el usuario: ' . $e->getMessage());
        }
    }
}
