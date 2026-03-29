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
        // Token valable 1h, supprimé après
        $token = auth()->user()->createToken(
            'stream-' . $camera->name,
            ['stream:read'],
            now()->addHour()
        )->plainTextToken;

        return view('pages.dashboard.cameras.show', compact('camera', 'token'));
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
        $path   = $request->input('path');   // nom de la caméra
        $action = $request->input('action'); // "publish" ou "read"
        $user   = $request->input('user');
        $pass   = $request->input('password');

        $camera = Camera::where('name', $path)->first();

        if (!$camera) {
            return response()->json(['error' => 'Camera not found'], 401);
        }

        // Publication : vérifie le mot de passe du flux
        if ($action === 'publish') {
            if ($pass === $camera->stream_pass) {
                return response()->json(['success' => true]);
            }
            return response()->json(['error' => 'Wrong password'], 401);
        }

        // Lecture : vérifie que le token appartient au bon user
        if ($action === 'read') {
            $token = \Laravel\Sanctum\PersonalAccessToken::findToken($pass);

            if (!$token) {
                return response()->json(['error' => 'Invalid token'], 401);
            }

            $user = $token->tokenable;

            // Vérifie que la caméra appartient à cet user
            if ($camera->owner_id === $user->id) {
                return response()->json(['success' => true]);
            }

            return response()->json(['error' => 'Forbidden'], 403);
        }

        return response()->json(['error' => 'Unknown action'], 400);
    }


}
