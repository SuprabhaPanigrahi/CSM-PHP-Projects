<?php
namespace App\Traits;

trait LoggerTrait {
    public function log($message) {
        $logFile = BASE_PATH . '/logs/app.log';
        $timestamp = date('Y-m-d H:i:s');
        file_put_contents($logFile, "[$timestamp] $message\n", FILE_APPEND);
    }
}