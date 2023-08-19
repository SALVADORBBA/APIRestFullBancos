<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LogController extends Controller
{


    public function showLogs()
    {
        $logFilePath = storage_path('logs/laravel.log');
        $logContents = file_get_contents($logFilePath);

        $logs = explode(PHP_EOL, $logContents);
        $infoLogs = [];

        foreach ($logs as $log) {
            if (strpos($log, 'local.INFO') !== false) {
                $infoLogs[] = $log;
            }
        }

        return view('logs', ['logs' => $infoLogs]);
    }
}
