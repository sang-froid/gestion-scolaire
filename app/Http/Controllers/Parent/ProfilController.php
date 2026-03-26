<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfilController extends Controller
{
    /**
     * Afficher le profil du parent connecté
     */
    public function index()
    {
        $parent = auth()->user()->parentModel;
        $user   = auth()->user();

        return view('parent.profil', compact('parent', 'user'));
    }

    /**
     * Mettre à jour les informations personnelles
     */
    public function updateInfos(Request $request)
    {
        $parent = auth()->user()->parentModel;

        $validated = $request->validate([
            'civilite'              => 'nullable|in:M.,Mme',
            'prenom'                => 'required|string|max:100',
            'nom'                   => 'required|string|max:100',
            'lien_parente'          => 'required|string|max:50',
            'telephone'             => 'required|string|max:20',
            'telephone_secondaire'  => 'nullable|string|max:20',
            'adresse'               => 'nullable|string|max:255',
            'ville'                 => 'nullable|string|max:100',
            'arrondissement'        => 'nullable|string|max:100',
            'code_postal'           => 'nullable|string|max:10',
        ]);

        $parent->update($validated);

        // Mettre à jour le name dans users aussi
        auth()->user()->update([
            'name' => trim($request->prenom . ' ' . $request->nom),
        ]);

        return back()->with('success_infos', 'Vos informations ont été mises à jour avec succès.');
    }

    /**
     * Mettre à jour l'email
     */
    public function updateEmail(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:users,email,' . auth()->id(),
        ]);

        auth()->user()->update(['email' => $validated['email']]);

        return back()->with('success_email', 'Votre adresse e-mail a été mise à jour.');
    }

    /**
     * Mettre à jour le mot de passe
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password'      => 'required',
            'password'              => ['required', 'confirmed', Password::min(8)],
        ]);

        if (!Hash::check($request->current_password, auth()->user()->password)) {
            return back()->withErrors(['current_password' => 'Le mot de passe actuel est incorrect.'])
                         ->with('tab', 'securite');
        }

        auth()->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success_password', 'Votre mot de passe a été modifié avec succès.')
                     ->with('tab', 'securite');
    }
}