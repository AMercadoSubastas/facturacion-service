<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ListMiddlewares extends Command
{
    protected $signature = 'middleware:list';
    protected $description = 'Listar middlewares registrados';

    public function handle()
    {
        $kernel = app(\App\Http\Kernel::class);
        $routeMiddleware = $kernel->getRouteMiddleware();

        foreach ($routeMiddleware as $key => $class) {
            $this->info("{$key}: {$class}");
        }
    }
}

