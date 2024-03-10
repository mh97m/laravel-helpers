<?php

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

if (! function_exists('redisGetter')) {
    /**
     * @param  string  $cache_key
     */
    function redisGetter($cache_key = '')
    {
        if ($cache_key == '') {
            throw new Exception("Cache key can't be empty for redis getter");
        }

        $cache_result = Redis::get($cache_key);

        return $cache_result ? unserialize($cache_result) : null;
    }
}

if (! function_exists('redisSetter')) {
    /**
     * @param  string  $cache_key
     * @param  string|int|array  $data
     */
    function redisSetter($cache_key = '', $data = null, $life_time = null)
    {
        if (! $life_time) {
            $life_time = $_ENV['CACHE_TIME'];
        }

        if (is_null($data) || $cache_key == '') {
            throw new Exception("Data or cache key can't be null for redis setter");
        }

        Redis::setex($cache_key, $life_time, serialize($data));

        return $data;
    }
}

if (! function_exists('redisWiper')) {
    /**
     * @param  string  $pattern
     */
    function redisWiper($pattern = '*')
    {
        try {
            $cache_keys = Redis::keys($pattern);
            foreach ($cache_keys as $cache_key) {
                if (str_contains($cache_key, 'asia')) {
                    Redis::del(str_replace('laravel_database_asia_', '', $cache_key));
                }
            }

        } catch (\Exception $e) {
            Log::channel('single')
                ->error('Wipe Redis not completed! ----- error message = '.$e?->getMessage().' ----- line = '.$e?->getLine().' ----- file = '.$e?->getFile());
        }
    }
}
