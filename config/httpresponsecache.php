<?php

return [
  'driver' => env('HTTP_RESPONSE_CACHE_DRIVER', 'file'),
  'seconds' => env('HTTP_RESPONSE_CACHE_LIFETIME', 60 * 60 * 24 * 10),
];


/*Route::get('/', function () {
//    return Cache::flush();
    return Cache::forget('products_rest_2:rest_2*');
    return Cache::tags('products_rest_2:rest_2')->flush();

    return view('welcome');
});

Route::get('/test', 'CalculatorController@calculate');
Route::get('/cache', 'CacheController@index')->middleware('cacheResponse:1444, products:rest');//->middleware('cache');*/