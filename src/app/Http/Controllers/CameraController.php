<?php

namespace App\Http\Controllers;

use App\Models\Camera;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class CameraController extends Controller
{
    // Liste des caméras de l'user connecté
    public function index()
    {
        $cameras = Camera::where('owner_id', auth()->id())->get();
        return view('pages.cameras.index', compact('cameras'));
    }

    // Formulaire d'ajout
    public function create()
    {
        return view('pages.cameras.create');
    }

    // Enregistre une nouvelle caméra
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:100|unique:cameras',
            'label'       => 'required|string|max:255',
            'stream_pass' => 'required|string|max:100',
        ]);

        Camera::create([
            'name'        => $request->name,
            'label'       => $request->label,
            'stream_pass' => $request->stream_pass,
            'stream_user' => 'admin',
            'owner_id'    => auth()->id(),
        ]);

        return redirect()->route('cameras.index')->with('success', 'Caméra ajoutée !');
    }

    // Vue d'une caméra
    public function show(string $name)
    {
        $camera = Camera::where('name', $name)
            ->where('owner_id', auth()->id())
            ->firstOrFail();

        $serverIp = config('app.mediamtx_host');

        return view('pages.cameras.show', compact('camera', 'serverIp'));
    }

    // Supprime une caméra
    public function destroy(Request $request)
    {
        $camera = Camera::where('id', $request->id)
            ->where('owner_id', auth()->id())
            ->firstOrFail();

        $camera->delete();

        return redirect()->route('cameras.index')->with('success', 'Caméra supprimée.');
    }
}
