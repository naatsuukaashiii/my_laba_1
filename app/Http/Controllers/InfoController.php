<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;

class InfoController extends Controller
{
    public function serverInfo(): JsonResponse
    {
        return response()->json(['php_version' => phpversion()]);
    }

    public function clientInfo(Request $request): JsonResponse
    {
        return response()->json([
            'ip' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
        ]);
    }


    public function databaseInfo() {
        $connection = DB::connection()->getPdo();
        return response()->json([
            'driver' => DB::connection()->getDriverName(),
            'database' => DB::connection()->getDatabaseName(),
        ]);
    }
}
