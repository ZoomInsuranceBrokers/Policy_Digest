<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\User;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();
            if ($user->role_id == 1) {
                return redirect()->intended('/admin/dashboard');
            } else {
                return redirect()->intended('/user/dashboard');
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    /**
     * Show the forgot password form
     */
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle the forgot password request
     */
    public function submitForgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        // Generate a password reset token
        $token = Str::random(64);

        // Store the token in password_resets table or your preferred method
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'email' => $request->email,
                'token' => $token,
                'created_at' => Carbon::now()
            ]
        );

        // Send the password reset email
        $emailSent = $this->sendPasswordResetEmail($request->email, $token);

        if ($emailSent) {
            return back()->with('success', 'Password reset link has been sent to your email address.');
        } else {
            return back()->withErrors(['error' => 'Failed to send password reset email. Please try again or contact support.']);
        }
    }

    /**
     * Send password reset email
     */
    private function sendPasswordResetEmail($email, $token)
    {
        $user = User::where('email', $email)->first();
        
        $resetUrl = url('/reset-password/' . $token . '?email=' . urlencode($email));

        try {
            Mail::send('emails.password-reset', [
                'user' => $user,
                'resetUrl' => $resetUrl,
                'token' => $token
            ], function ($message) use ($email) {
                $message->to($email)
                        ->subject('Password Reset Request');
            });

            // If we reach here without exception, mail was sent successfully
            Log::info('Password reset email sent successfully to: ' . $email);
            return true;

        } catch (\Exception $e) {
            // Log the error or handle it as needed
            Log::error('Failed to send password reset email: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Show the reset password form
     */
    public function showResetPasswordForm($token, Request $request)
    {
        $email = $request->query('email');
        
        // Verify the token exists and is not expired (24 hours)
        $passwordReset = DB::table('password_reset_tokens')
            ->where('token', $token)
            ->where('email', $email)
            ->where('created_at', '>', Carbon::now()->subHours(24))
            ->first();

        if (!$passwordReset) {
            return redirect('/forgot-password')->withErrors(['error' => 'Invalid or expired reset token.']);
        }

        return view('auth.reset-password', compact('token', 'email'));
    }

    /**
     * Handle the password reset
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        // Verify the token
        $passwordReset = DB::table('password_reset_tokens')
            ->where('token', $request->token)
            ->where('email', $request->email)
            ->where('created_at', '>', Carbon::now()->subHours(24))
            ->first();

        if (!$passwordReset) {
            return back()->withErrors(['error' => 'Invalid or expired reset token.']);
        }

        // Update the user's password
        $user = User::where('email', $request->email)->first();
        $user->password = bcrypt($request->password);
        $user->save();

        // Delete the password reset token
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect('/login')->with('success', 'Password has been reset successfully. Please login with your new password.');
    }
}
