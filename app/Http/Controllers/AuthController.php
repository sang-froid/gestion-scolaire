<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
class AuthController extends Controller
{
    //


     // afficher la page login
    public function showLogin()
    {
        return view('auth.login');
    }

    // traiter le login
    public function login(Request $request)
    {
        // logique login
        return "Traitement du login";
    }

    // afficher la page register
    public function showRegister()
    {
        return view('auth.register');
    }

    // traiter l'inscription
    public function register(Request $request)
    {
        // logique register
        return "Traitement du register";
    }


     // ── GET /login ────────────────────────────────────────────
    public function showForm()
    {
        // Si déjà connecté → rediriger directement
        if (Auth::check()) {
            return $this->redirectSelonRole(Auth::user());
        }

        return view('auth.login');
    }

    // ── POST /login ───────────────────────────────────────────
    public function authenticate(Request $request)
    {
        Log::info('[LOGIN] Tentative de connexion : ' . $request->email);

        // Validation des champs
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required'    => "L'adresse e-mail est obligatoire.",
            'email.email'       => "L'adresse e-mail n'est pas valide.",
            'password.required' => 'Le mot de passe est obligatoire.',
        ]);

        // Tentative de connexion
        $credentials = $request->only('email', 'password');
        $remember    = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $user = Auth::user();
            Log::info('[LOGIN] Connexion réussie : ' . $user->email . ' / role=' . $user->role);

            // Vérifier que le compte est actif
            if (!$user->actif) {
                Auth::logout();
                Log::warning('[LOGIN] Compte inactif : ' . $user->email);
                return back()->withErrors([
                    'email' => 'Votre compte est désactivé. Contactez l\'administration.',
                ])->onlyInput('email');
            }

            return $this->redirectSelonRole($user);
        }

        // Échec de connexion
        Log::warning('[LOGIN] Échec connexion pour : ' . $request->email);

        return back()->withErrors([
            'email' => 'Identifiants incorrects. Vérifiez votre email et mot de passe.',
        ])->onlyInput('email');
    }

    // ── POST /logout ──────────────────────────────────────────
    public function logout(Request $request)
    {
        Log::info('[LOGIN] Déconnexion : ' . Auth::user()?->email);

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    // ── Redirection selon le rôle ─────────────────────────────
    private function redirectSelonRole($user)
    {
        return match($user->role) {
            'admin'  => redirect()->route('admin.dashboard')
                                  ->with('success', 'Bienvenue, ' . $user->name . ' !'),
            'parent' => redirect()->route('parent.dashboard')
                                  ->with('success', 'Bienvenue, ' . $user->name . ' !'),
            default  => redirect('/'),
        };
    }
}
