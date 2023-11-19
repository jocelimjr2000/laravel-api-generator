<?php

namespace JocelimJr\LaravelApiGenerator;

use JocelimJr\LaravelApiGenerator\Console\ApiCreateCommand;
use JocelimJr\LaravelApiGenerator\Console\ApiGenerateCommand;
use Illuminate\Support\ServiceProvider;

class LaravelGeneratorServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        $this->registerCommands();
    }

    public function boot(): void
    {
        $this->loadConfig();

        $this->loadTranslationsFrom(__DIR__.'/../lang', 'laravel-generator');
    }

    private function loadConfig(): void
    {
        $ref = 'laravel-generator';

        $path = realpath(__DIR__ . '/../config/' . $ref . '.php');
        $this->publishes([$path => config_path($ref . '.php')], 'config');
        $this->mergeConfigFrom($path, $ref);
    }

    private function registerCommands(): void
    {
        $commands = [
            'ApiGenerate' => 'command.api.generate',
            'ApiCreate' => 'command.api.create',
        ];

        foreach (array_keys($commands) as $command) {
            $method = "register{$command}Command";

            call_user_func_array([$this, $method], []);
        }

        $this->commands(array_values($commands));
    }

    private function registerApiGenerateCommand(): void
    {
        $this->app->singleton('command.api.generate', function ($app) {
            return new ApiGenerateCommand($app['files']);
        });
    }

    private function registerApiCreateCommand(): void
    {
        $this->app->singleton('command.api.create', function ($app) {
            return new ApiCreateCommand($app['files']);
        });
    }
}
