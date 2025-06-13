<?php

use Illuminate\Support\Facades\Log;

if (!function_exists('logMessage')) {
    function logMessage($endpoint, $input, $exception)
    {
        Log::info('Endpoint: = ' . $endpoint);
        Log::info($input);
        Log::info('Exception: = ' . $exception);
    }
}
