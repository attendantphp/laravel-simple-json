<?php

namespace Attendant\Laravel\SimpleJson;

use Attendant\Core\Formatter\Formatter;
use Attendant\Core\Parser\Parser;
use Attendant\Core\Resolver\Resolver;
use Attendant\Laravel\SimpleJson\Formatter\LaravelSimpleJsonFormatter;
use Attendant\Laravel\SimpleJson\Parser\LaravelSimpleJsonParser;
use Attendant\Laravel\SimpleJson\Resolver\EloquentResolver;
use Attendant\Laravel\SimpleJson\Resolver\EloquentTypeResolver;
use Illuminate\Support\ServiceProvider;

class LaravelSimpleJsonServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/simple-json-api.php', 'simple-json-api'
        );

        $this->app->singleton(
            EloquentTypeResolver::class,
            fn () => new EloquentTypeResolver(config('simple-json-api.type_map'))
        );
        $this->app->bind(Formatter::class, LaravelSimpleJsonFormatter::class);
        $this->app->bind(Parser::class, LaravelSimpleJsonParser::class);
        $this->app->bind(Resolver::class, EloquentResolver::class);
    }

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/simple-json-api.php' => config_path('simple-json-api.php'),
        ]);
    }
}