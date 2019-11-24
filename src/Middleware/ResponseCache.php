<?php

namespace Armen\ResponseCache\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;
use Armen\ResponseCache\ResponseCache as CacheResponse;

class ResponseCache
{

    protected $responseCache;

    public function __construct(CacheResponse $responseCache)
    {
        $this->responseCache = $responseCache;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, ...$args)
    {
        $response = $next($request);

        if ($this->responseCache->isValidResponse($request, $response)) {
            return $this->responseCache->getOrPutCache($request, $response, $args);
        }

        return $response;
    }
}