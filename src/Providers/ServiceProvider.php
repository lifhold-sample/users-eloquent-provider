<?php

declare(strict_types=1);

namespace Lifhold\Users\Eloquent\Providers;

use Lifhold\Users\Contracts\UsersService;
use Lifhold\Users\Eloquent\Services\UsersEloquentService;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    protected array $binds = [
        UsersService::class => UsersEloquentService::class
    ];

    public function register()
    {
        foreach ($this->binds as $bind => $service) {
            $this->app->bind($bind, $service);
        }

        $this->loadMigrationsFrom(__DIR__ . "/../../database/migrations");
    }
}
