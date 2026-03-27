<?php

namespace App\Http\Controllers;

use App\Models\Camera;

class DashboardController extends Controller
{
    public function index()
    {
        $activeCameras    = Camera::where('owner_id', auth()->id())
            ->where('is_active', true)
            ->get();

        $totalUserCameras = Camera::where('owner_id', auth()->id())->count();
        $serverIp         = config('app.mediamtx_host');

        return view('dashboard.index', compact('activeCameras', 'totalUserCameras', 'serverIp'));
    }
}
