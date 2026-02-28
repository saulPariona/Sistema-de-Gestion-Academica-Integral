<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Services\AuditoriaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->with('error', 'Credenciales incorrectas.')->withInput();
        }

        if ($user->estaBloqueado()) {
            return back()->with('error', 'Tu cuenta está bloqueada temporalmente. Intenta más tarde.')->withInput();
        }

        if ($user->estado === 'inactivo') {
            return back()->with('error', 'Tu cuenta está desactivada. Contacta al administrador.')->withInput();
        }

        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user->increment('intentos_fallidos');

            if ($user->intentos_fallidos >= 5) {
                $user->update([
                    'estado' => 'bloqueado',
                    'bloqueado_hasta' => now()->addMinutes(30),
                ]);
                AuditoriaService::registrar('bloqueo_por_intentos', 'User', $user->id);
                return back()->with('error', 'Cuenta bloqueada por múltiples intentos fallidos.')->withInput();
            }

            return back()->with('error', 'Contraseña incorrecta.')->withInput();
        }

        $user->update([
            'intentos_fallidos' => 0,
            'bloqueado_hasta' => null,
            'ultimo_acceso' => now(),
        ]);

        if ($user->estado === 'bloqueado' && $user->bloqueado_hasta && $user->bloqueado_hasta->isPast()) {
            $user->update(['estado' => 'activo']);
        }

        $request->session()->regenerate();
        session(['ultima_actividad' => time()]);

        AuditoriaService::registrar('inicio_sesion', 'User', $user->id);

        return match ($user->rol) {
            User::ROL_ADMINISTRADOR => redirect()->route('admin.dashboard'),
            User::ROL_DOCENTE => redirect()->route('docente.dashboard'),
            User::ROL_ESTUDIANTE => redirect()->route('estudiante.dashboard'),
        };
    }

    public function logout(Request $request)
    {
        AuditoriaService::registrar('cierre_sesion', 'User', Auth::id());

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.required' => 'El correo es obligatorio.',
            'email.exists' => 'No encontramos una cuenta con ese correo.',
        ]);

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', 'Se envió un enlace de recuperación a tu correo.')
            : back()->with('error', 'No se pudo enviar el enlace. Intenta de nuevo.');
    }

    public function showResetPassword(string $token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/[A-Z]/',
                'regex:/[a-z]/',
                'regex:/[0-9]/',
                'confirmed',
            ],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->setRememberToken(Str::random(60));
                $user->save();
                AuditoriaService::registrar('reset_password', 'User', $user->id);
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', 'Contraseña actualizada correctamente.')
            : back()->with('error', 'No se pudo restablecer la contraseña.');
    }

    public function showChangePassword()
    {
        return view('auth.change-password');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/[A-Z]/',
                'regex:/[a-z]/',
                'regex:/[0-9]/',
                'confirmed',
            ],
        ]);

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'La contraseña actual es incorrecta.');
        }

        $user->update(['password' => Hash::make($request->password)]);

        AuditoriaService::registrar('cambio_password', 'User', $user->id);

        return back()->with('status', 'Contraseña actualizada correctamente.');
    }
}
