<?php


namespace Armen\ResponseCache;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redis;

class ResponseCache
{

    protected $args = [];

    public $tags = null;

    public function getOrPutCache(Request $request, $response, $args)
    {
//        $keys = Redis::keys('*products_rest_2*');
//
//        $keys = str_replace(['laravel_database_', 'v2api_database_'], '', implode(', ', $keys));
//        Redis::del(explode(', ', $keys));
//        return 1;
        $cacheDriver = $this->getCacheDriver();

        $seconds = $this->getCacheLifeTime($args);
        $key = $this->getKey($request, $args);

        if ($cacheDriver == 'redis') {

            $data = Redis::get($key);
            if (isset($data) && !empty($data)) {
                return Cache::remember($key, $seconds, function () use ($request, $response) {
                    return $response;
                });
            } else {
                $cacheData = $response->getContent();
                Redis::setex($key, $seconds, $response->getContent());
            }

            return $response;
        }

        if (isset($this->tags)) {
            return Cache::tags($this->tags)->remember($key, $seconds, function () use ($request, $response) {
                return $response;
            });
        } else {
            return Cache::remember($key, $seconds, function () use ($request, $response) {
                return $response;
            });
        }
    }

    protected function getCacheLifeTime(&$args)
    {
        return count($args) > 0 && is_numeric($args[0]) ? (int) array_shift($args) : (int) config('httpresponsecache.seconds');
    }

    public function isValidResponse(Request $request, $response)
    {
        return $request->isMethod('GET') && $response->getStatusCode() == 200;
    }

    protected function getKey($request, $args)
    {

        if (count($args) > 0) {

            $key = explode(':', trim($args[0]));
            $formattedKey = $key[0];
            $this->tags = $formattedKey;

            if (isset($key[1]) && $request->has($key[1])) {
                $parameters = str_replace('=', '_', http_build_query($request->query()));
                $formattedKey = $key[0] . "_{$key[1]}_" . $request->get($key[1]) . ':' . $parameters;
                $this->tags = $formattedKey;
            }

            return $formattedKey;
        }

        return $request->fullUrl();
    }

    protected function getCacheDriver()
    {
        return env('CACHE_DRIVER', 'redis');
        return config('httpresponsecache.driver');
    }
}