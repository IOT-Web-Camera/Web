<?php

namespace App\Http\Controllers;

use App\Models\Camera;
use Illuminate\Routing\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $activeCameras    = Camera::where('owner_id', auth()->id())
            ->where('is_active', true)
            ->get();
        $totalUserCameras = Camera::where('owner_id', auth()->id())->count();
        $serverIp         = config('app.mediamtx_host');

        // Token valable 2h pour le stream
        $streamToken = auth()->user()
            ->createToken('stream', ['stream:read'], now()->addHours(2))
            ->plainTextToken;

        return view('pages.dashboard.index', compact(
            'activeCameras', 'totalUserCameras', 'serverIp', 'streamToken'
        ));
    }
}
