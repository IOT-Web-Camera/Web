<?php

namespace App\Http\Controllers;

use App\Models\Camera;
use Illuminate\Routing\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $userCameras      = Camera::where('owner_id', auth()->id())->get();
        $totalUserCameras = $userCameras->count();
        $serverIp         = config('app.mediamtx_host');

        // Caméra considérée active si heartbeat < 35 secondes
        $activeCameras = $userCameras->filter(
            fn($cam) => $cam->last_heartbeat &&
                $cam->last_heartbeat->gt(now()->subSeconds(35))
        );

        $streamToken = auth()->user()
            ->createToken('stream', ['stream:read'], now()->addHours(2))
            ->plainTextToken;

        return view('pages.dashboard.index', compact(
            'activeCameras', 'totalUserCameras', 'serverIp', 'streamToken'
        ));
    }
}
