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
    public function show(Camera $camera)
    {
        if ($camera->owner_id !== auth()->id()) {
            abort(403);
        }

        $serverIp = config('app.mediamtx_host'); // ← ajoute ça

        $token = auth()->user()->createToken(
            'stream-' . $camera->name,
            ['stream:read'],
            now()->addHour()
        )->plainTextToken;

        return view('pages.cameras.show', compact('camera', 'token', 'serverIp'));
    }

    // Supprime une caméra
    public function destroy(Camera $camera)
    {
        if ($camera->owner_id !== auth()->id()) {
            abort(403);
        }

        $camera->delete();

        return redirect()
            ->route('cameras.index')
            ->with('success', 'Caméra supprimée.');
    }



    public function stream(string $name)
    {
        $camera = Camera::where('name', $name)
            ->where('owner_id', auth()->id())
            ->firstOrFail();

        $serverIp = config('app.mediamtx_host');

        // Redirige vers MediaMTX avec les credentials côté serveur
        $url = "http://{$serverIp}:8889/{$camera->name}/";

        $response = \Illuminate\Support\Facades\Http::withBasicAuth(
            $camera->stream_user,
            $camera->stream_pass
        )->get($url);

        return response($response->body(), 200)
            ->header('Content-Type', $response->header('Content-Type'));
    }


    public function mediamtxAuth(Request $request)
    {
        $action = $request->input('action');

        // Lecture toujours autorisée
        if ($action === 'read') {
            return response()->noContent();
        }

        // Publication : vérifie le mot de passe
        if ($action === 'publish') {
            $path   = $request->input('path');
            $pass   = $request->input('password', '');

            $camera = Camera::where('name', $path)->first();

            if (!$camera) {
                return response()->json(['error' => 'Camera not found'], 401);
            }

            if ($pass === $camera->stream_pass) {
                return response()->noContent();
            }

            return response()->json(['error' => 'Wrong password'], 401);
        }

        return response()->noContent();
    }


    public function heartbeat(Request $request)
    {
        $name   = $request->input('path');
        $action = $request->input('action');

        if (in_array($action, ['publish', 'read'])) {
            Camera::where('name', $name)->update([
                'last_heartbeat' => now(),
                'is_active'      => true,
            ]);
        }

        if ($action === 'unpublish') {
            Camera::where('name', $name)->update(['is_active' => false]);
        }

        return response()->noContent();
    }


    public function cameraStatus(Request $request)
    {
        $name    = $request->input('camera_name');
        $payload = $request->input('payload', []);

        $camera = Camera::where('name', $name)->first();

        if (!$camera) {
            return response()->json(['error' => 'Camera not found'], 404);
        }

        // Met à jour les infos remontées par la caméra
        $camera->update([
            'last_heartbeat' => now(),
            'is_active'      => true,
        ]);

        // Tu pourras stocker d'autres infos plus tard
        // ex: température, état LED, position servo...
        \Log::info("Status caméra {$name}", $payload);

        return response()->noContent();
    }






}
