<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AdminService;

class AdminControlller extends Controller
{
 


    protected $adminService;

    public function __construct(AdminService $adminService)
    {
        $this->adminService = $adminService;
    }
    public function statsRecruteur(Request $request)
    {
        $user = $request->user();
    
        if ($user->role !== 'recruteur') {
            return response()->json(['message' => 'Accès interdit'], 403);
        }

        $stats = $this->adminService->getRecruteurStats($user->id);
        return response()->json($stats);
    }
    public function statsGlobales(Request $request)
    {
        $user = $request->user();
        if ($user->role !== 'admin') {
            return response()->json(['message' => 'Accès interdit'], 403);
        }
        $stats = $this->adminService->getGlobalStats();
        return response()->json($stats);
    }
}


