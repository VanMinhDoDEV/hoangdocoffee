<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function provinces()
    {
        $path = storage_path('app/json_address/provinces.json');
        if (!file_exists($path)) {
            return response()->json(['error' => 'Data not found'], 404);
        }
        $content = file_get_contents($path);
        return response($content, 200, ['Content-Type' => 'application/json']);
    }

    public function communes($code)
    {
        // Sanitize code to prevent directory traversal
        if (!preg_match('/^[a-zA-Z0-9]+$/', $code)) {
            return response()->json(['error' => 'Invalid code'], 400);
        }
        
        $path = storage_path("app/json_address/communes/{$code}.json");
        if (!file_exists($path)) {
            return response()->json(['error' => 'Data not found'], 404);
        }
        $content = file_get_contents($path);
        return response($content, 200, ['Content-Type' => 'application/json']);
    }
}
