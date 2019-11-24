<?php

namespace Armen\ResponseCache\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class ResponseCacheKiller extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'httpresponsecache:kill {key?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $key = $this->argument('key');
        if ($key) {
            $cacheKilled = $this->forget($key);
        } else {
            $cacheKilled = $this->kill();
        }

        return $cacheKilled;
    }

    protected function forget($key)
    {
        Cache::forget('products_rest_2:rest_2');

        $this->info('Response cache ' . $key . ' killed!');
    }

    protected function kill()
    {
        Cache::flush();

        $this->info('Response cache killed!');
    }
}
