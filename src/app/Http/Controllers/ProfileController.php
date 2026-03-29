<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Affiche la page de profil
     */
    public function edit(Request $request)
    {
        return view('pages.profile.index', [
            'user' => $request->user()
        ]);
    }

    /**
     * Met à jour les informations du profil
     */
    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'email'],
        ]);

        $user->update($validated);

        return back()->with('success', 'Profil mis à jour.');
    }

    /**
     * Supprime le compte utilisateur
     */
    public function destroy(Request $request)
    {
        $user = $request->user();

        // Déconnexion
        auth()->logout();

        // Suppression
        $user->delete();

        // Invalider la session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Votre compte a été supprimé.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        if (!Hash::check($request->current_password, $request->user()->password)) {
            return back()->withErrors(['current_password' => 'Mot de passe actuel incorrect.']);
        }

        $request->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Mot de passe mis à jour.');
    }


}
