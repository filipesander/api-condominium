<?php

namespace App\Helpers;

class Logs
{
    /**
     * Generate new log configuration
     * @param string $name
     * @param integer $days
     * @return array
     */
    public static function custom_log(string $name, int $days = 14): array
    {
        return [
            "_{$name}" => ["driver" => "daily", "days" => $days, "path" => storage_path("logs/{$name}/{$name}.log"), "level" => env("LOG_LEVEL", "debug")],
            "{$name}" => [ "driver" => "stack", "channels" => ["_{$name}", "global"], "level" => env("LOG_LEVEL", "debug")],
        ];
    }
}
