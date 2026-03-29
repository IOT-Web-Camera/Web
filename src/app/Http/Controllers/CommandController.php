<?php

namespace App\Http\Controllers;

use App\Models\Camera;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class CommandController extends Controller
{
    // Adresse interne du bridge (jamais exposée au client)
    private string $bridgeUrl = 'ws://localhost:8765';

    /**
     * Méthode centrale qui valide et transmet la commande
     */
    private function dispatch(string $cameraName, string $action, array $payload): \Illuminate\Http\JsonResponse
    {
        // 1. Vérifie que la caméra appartient bien à l'user connecté
        $camera = Camera::where('name', $cameraName)
            ->where('owner_id', auth()->id())
            ->firstOrFail();

        // 2. Construit l'ordre
        $order = [
            'device_id'  => 'laravel_server',
            'action'     => 'CMD_' . $action,
            'target_cam' => $camera->name,
            'payload'    => $payload,
        ];

        // 3. Envoie au bridge via HTTP (voir plus bas)
        $this->sendToBridge($order);

        return response()->json(['ok' => true]);
    }

    public function led(Request $request, string $name)
    {
        $request->validate(['state' => 'required|in:ON,OFF']);
        return $this->dispatch($name, 'LED', ['state' => $request->state]);
    }

    public function move(Request $request, string $name)
    {
        $request->validate(['direction' => 'required|in:left,center,right']);
        return $this->dispatch($name, 'MOVE', ['direction' => $request->direction]);
    }

    public function reboot(Request $request, string $name)
    {
        return $this->dispatch($name, 'REBOOT', []);
    }

    /**
     * Envoie l'ordre au bridge Python via HTTP
     */
    private function sendToBridge(array $order): bool
    {
        try {
            $response = \Illuminate\Support\Facades\Http::timeout(3)
                ->post('http://localhost:8766/cmd', $order);

            return $response->successful();
        } catch (\Exception $e) {
            \Log::error('Bridge injoignable', ['error' => $e->getMessage()]);
            return false;
        }
    }

}
