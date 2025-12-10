<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\Participante;
use App\Models\Role;
use App\Mail\VerificationCodeMail;

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
            // Generar código de verificación de 6 dígitos
            $verificationCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            
            // 1. Crear usuario en tabla users (sin verificar)
            $user = User::create([
                'name' => $request->username,
                'email' => $request->correo,
                'password' => $request->password,
                'is_active' => false, // Usuario inactivo hasta verificar
                'verification_code' => $verificationCode,
                'verification_code_expires_at' => now()->addMinutes(15),
            ]);

            // 2. Crear participante
            Participante::create([
                'user_id' => $user->id,
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
                    'user_id' => $user->id,
                    'Id_Rol' => $roleParticipante->Id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::commit();

            // Enviar correo con código de verificación
            Mail::to($user->email)->send(new VerificationCodeMail($verificationCode, $request->nombre));

            // Guardar email en sesión para la página de verificación
            session(['verification_email' => $user->email]);

            return redirect()->route('verify.show')->with('success', '¡Se ha enviado un código de verificación a tu correo!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al registrar usuario: ' . $e->getMessage()])->withInput();
        }
    }

    public function showVerifyForm()
    {
        $email = session('verification_email');
        
        if (!$email) {
            return redirect()->route('register')->withErrors(['error' => 'No hay verificación pendiente. Por favor regístrate primero.']);
        }

        return view('auth.verify', compact('email'));
    }

    public function verifyCode(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        $email = session('verification_email');
        
        if (!$email) {
            return redirect()->route('register')->withErrors(['error' => 'Sesión expirada. Por favor regístrate de nuevo.']);
        }

        $user = User::where('email', $email)->first();

        if (!$user) {
            return back()->withErrors(['code' => 'Usuario no encontrado.']);
        }

        // Verificar que el código no haya expirado
        if ($user->verification_code_expires_at && now()->greaterThan($user->verification_code_expires_at)) {
            return back()->withErrors(['code' => 'El código ha expirado. Por favor solicita uno nuevo.']);
        }

        // Verificar el código
        if ($user->verification_code !== $request->code) {
            return back()->withErrors(['code' => 'El código ingresado es incorrecto.']);
        }

        // Activar usuario y limpiar código
        $user->update([
            'is_active' => true,
            'email_verified_at' => now(),
            'verification_code' => null,
            'verification_code_expires_at' => null,
        ]);

        // Limpiar sesión
        session()->forget('verification_email');

        return redirect()->route('login')->with('success', '¡Tu cuenta ha sido verificada! Ahora puedes iniciar sesión.');
    }

    public function resendCode()
    {
        $email = session('verification_email');
        
        if (!$email) {
            return redirect()->route('register')->withErrors(['error' => 'No hay verificación pendiente.']);
        }

        $user = User::where('email', $email)->first();

        if (!$user) {
            return redirect()->route('register')->withErrors(['error' => 'Usuario no encontrado.']);
        }

        // Generar nuevo código
        $verificationCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        
        $user->update([
            'verification_code' => $verificationCode,
            'verification_code_expires_at' => now()->addMinutes(15),
        ]);

        // Enviar correo
        $participante = $user->participante;
        $nombre = $participante ? $participante->Nombre : $user->name;
        
        Mail::to($user->email)->send(new VerificationCodeMail($verificationCode, $nombre));

        return back()->with('success', '¡Se ha enviado un nuevo código a tu correo!');
    }
}
