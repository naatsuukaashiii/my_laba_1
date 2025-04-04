<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
class InfoController extends Controller
{
    public function serverInfo()
    {
        return response()->json(['php_version' => phpversion()]);
    }
    public function clientInfo(Request $request)
    {
        return response()->json([
            'ip' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
        ]);
    }
    public function databaseInfo()
    {
        $config = DB::connection()->getConfig();
        return response()->json([
            'driver' => $config['driver'],
            'host' => $config['host'],
            'database' => $config['database'],
        ]);
    }
}
