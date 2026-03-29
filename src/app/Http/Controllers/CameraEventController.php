<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CameraEvent;
use Illuminate\Routing\Controller;

class CameraEventController extends Controller
{
    // Réception d'un événement depuis le bridge
    public function handle(Request $request)
    {
        CameraEvent::create([
            'device'  => $request->device,
            'type'    => $request->type,
            'payload' => $request->payload ?? [],
        ]);

        return ['ok' => true];
    }

    // Récupération des derniers événements pour le dashboard
    public function list($device)
    {
        return CameraEvent::where('device', $device)
            ->latest()
            ->take(20)
            ->get();
    }
}
