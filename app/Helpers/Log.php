<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

global $breaker, $start, $end;
$breaker = "\n|--------------------------------------------------------------------------|\n";
$start = '[ '.now()->format('Y-m-d H:i:s')." ]\n$breaker";
$end =
"\n=================================================\n".
"=================================================\n".
"=================================================\n";

if (! function_exists('appLogger')) {
    function appLogger(array $args = [])
    {
        $format = isset($args['format']) ? $args['format'] : 'log';
        unset($args['format']);

        $func_name = $format.'Logger';

        return $func_name($args);
    }
}

if (! function_exists('txtLogger')) {
    function txtLogger(array $args)
    {
        global $start;
        global $breaker;
        global $end;
        $type = isset($args['type']) ? $args['type'] : 'info';
        unset($args['type']);
        $file_name = isset($args['file_name']) ? $args['file_name'] : intval(microtime(true) * 1000);
        unset($args['file_name']);
        $message = isset($args['message']) ? $args['message'] : 'default';
        unset($args['message']);
        // $bigest_key_len = max(array_map('strlen', array_keys($args)));
        $log_text = $start;
        $log_text .= '|--'.strtoupper($type).' ==> [ '.$message.' ]'.$breaker;
        foreach ($args as $key => $value) {
            $log_text .= '|--'.strtoupper($key).' ==> [ '.$value.' ]'.$breaker;
        }
        $log_text .= $end;

        $ext = str_replace('Logger', '', __FUNCTION__);

        file_put_contents(storage_path("logs/$file_name.$ext"), $log_text.PHP_EOL, FILE_APPEND | LOCK_EX);

        return true;
    }
}

if (! function_exists('logLogger')) {
    function logLogger(array $args)
    {
        global $start;
        global $breaker;
        global $end;
        $type = isset($args['type']) ? $args['type'] : 'info';
        unset($args['type']);
        $file_name = isset($args['file_name']) ? $args['file_name'] : intval(microtime(true) * 1000);
        unset($args['file_name']);
        $message = isset($args['message']) ? $args['message'] : 'default';
        unset($args['message']);

        $log_text = $start;
        $log_text .= '|--'.strtoupper($type).' ==> [ '.$message.' ]'.$breaker;
        foreach ($args as $key => $value) {
            $log_text .= '|--'.strtoupper($key).' ==> [ '.$value.' ]'.$breaker;
        }
        $log_text .= $end;

        $ext = str_replace('Logger', '', __FUNCTION__);

        file_put_contents(storage_path("logs/$file_name.$ext"), $log_text.PHP_EOL, FILE_APPEND | LOCK_EX);

        return true;
    }
}

if (! function_exists('jsonLogger')) {
    function jsonLogger(array $args)
    {
        $file_name = isset($args['file_name']) ? $args['file_name'] : intval(microtime(true) * 1000);
        unset($args['file_name']);
        $path = storage_path("logs/$file_name.json");

        if (! file_exists($path)) {
            file_put_contents($path, json_encode([]));
        }

        $file_content = json_decode(file_get_contents($path));
        $file_content[] = $args;
        file_put_contents($path, json_encode($file_content));

        return true;
    }
}

if (! function_exists('dbLogger')) {
    function dbLogger(array $args)
    {
        $log = DB::table(strtolower($args['type']).'_logs')->insert(array_merge($args, [
            'created_at' => nowUTCDateTime(),
        ]));

        return $log ? true : false;
    }
}