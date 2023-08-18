<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LogController extends Controller
{


    public function showLogs()
    {
        $logFilePath = storage_path('logs/laravel.log');
        $logs = file_get_contents($logFilePath);

        return view('logs', ['logs' => explode(PHP_EOL, $logs)]);
    }
}
