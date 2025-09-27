<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class ForgotPasswordController extends Controller
{
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'g-recaptcha-response' => 'required'
        ], [
            'email.required' => 'El correo electrónico es obligatorio.',
            'g-recaptcha-response.required' => 'Debes completar el CAPTCHA.'
        ]);

        $secret = "6Lf9ltYrAAAAAOldLhmKpqm-i6vHSx8DPSw4myjH";
        $response = $request->input('g-recaptcha-response');

        $captchaValidation = Http::asForm()->post("https://www.google.com/recaptcha/api/siteverify", [
            'secret' => $secret,
            'response' => $response,
        ]);

        $captchaResult = $captchaValidation->json();

        if (!$captchaResult['success']) {
            return back()->with('error', 'Error en el CAPTCHA. Inténtalo de nuevo.');
        }

        $user = User::where('email', $request->input('email'))->first();

        $mensaje = 'Si el email existe, te enviaremos instrucciones para restablecer tu contraseña.';

        if (!$user) {
            return back()->with('success', $mensaje);
        }

        $token = Str::random(64);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->input('email')],
            ['token' => $token, 'created_at' => Carbon::now()]
        );

        Mail::send('emails.reset_password', ['token' => $token], function ($message) use ($request) {
            $message->to($request->input('email'));
            $message->subject('Recuperación de contraseña');
        });

        return back()->with('success', $mensaje);
    }

    public function showResetForm(Request $request)
    {
        $token = $request->query('token');

        $resetToken = DB::table('password_reset_tokens')->where('token', $token)->first();

        if (!$resetToken) {
            return redirect()->route('login')->with('error', 'El token de restablecimiento no es válido o ha expirado.');
        }

        return view('passwordrecovery.reset-password', ['token' => $token]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'password' => 'required|confirmed|min:6',
            'token' => 'required',
        ]);

        $resetToken = DB::table('password_reset_tokens')->where('token', $request->input('token'))->first();

        if (!$resetToken) {
            return back()->with('error', 'El token de restablecimiento no es válido o ha expirado.');
        }

        $expireTime = Carbon::parse($resetToken->created_at)->addHours(1);

        if (Carbon::now()->greaterThan($expireTime)) {
            return back()->with('error', 'El token de restablecimiento ha expirado.');
        }

        $user = User::where('email', $resetToken->email)->first();

        if (!$user) {
            return back()->with('error', 'Este email no está registrado.');
        }

        $user->password = Hash::make($request->input('password'));
        $user->save();

        DB::table('password_reset_tokens')->where('token', $request->input('token'))->delete();

        return redirect()->route('login')->with('success', 'Contraseña restablecida con éxito.');
    }
}
