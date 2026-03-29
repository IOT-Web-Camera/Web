<?php

namespace App\Http\Controllers;

use App\Models\Camera;
use Illuminate\Http\Request;
use App\Models\CameraEvent;
use Illuminate\Routing\Controller;

class CameraEventController extends Controller
{
    // Réception d'un événement depuis le bridge
    public function handle(Request $request)
    {
        // 1) Sécurité : limiter la taille du payload
        $payload = $request->payload;

        if (strlen(json_encode($payload)) > 2000) {
            $payload = ['error' => 'payload too large'];
        }

        // 2) Stockage de l'event
        CameraEvent::create([
            'device'  => $request->device,
            'type'    => $request->type,
            'payload' => $payload,
        ]);

        // 3) Purge : garder seulement les 200 derniers events par caméra
        CameraEvent::where('device', $request->device)
            ->orderBy('id', 'desc')
            ->skip(200)
            ->take(PHP_INT_MAX)
            ->delete();

        return ['ok' => true];
    }

    // Récupération des derniers événements pour le dashboard
    public function list($device)
    {
        // Vérifie que la caméra appartient à l'user connecté
        $camera = Camera::where('name', $device)
            ->where('owner_id', auth()->id())
            ->firstOrFail();

        return CameraEvent::where('device', $device)
            ->latest()
            ->take(20)
            ->get();
    }
}
