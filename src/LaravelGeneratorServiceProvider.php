<?php

namespace JocelimJr\LaravelApiGenerator;

use JocelimJr\LaravelApiGenerator\Console\ApiCreateCommand;
use JocelimJr\LaravelApiGenerator\Console\ApiGenerateCommand;
use JocelimJr\LaravelApiGenerator\Console\DataTransferObjectMakeCommand;
use JocelimJr\LaravelApiGenerator\Console\InterfaceMakeCommand;
use JocelimJr\LaravelApiGenerator\Console\RepositoryMakeCommand;
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
            'RepositoryMake' => 'command.repository.make',
            'InterfaceMake' => 'command.interface.make',
            'DataTransferObjectMake' => 'command.dto.make',
            'ApiGenerate' => 'command.api.generate',
            'ApiCreate' => 'command.api.create',
        ];

        foreach (array_keys($commands) as $command) {
            $method = "register{$command}Command";

            call_user_func_array([$this, $method], []);
        }

        $this->commands(array_values($commands));
    }

    private function registerRepositoryMakeCommand(): void
    {
        $this->app->singleton('command.repository.make', function ($app) {
            return new RepositoryMakeCommand($app['files']);
        });
    }

    private function registerInterfaceMakeCommand(): void
    {
        $this->app->singleton('command.interface.make', function ($app) {
            return new InterfaceMakeCommand($app['files']);
        });
    }

    private function registerDataTransferObjectMakeCommand(): void
    {
        $this->app->singleton('command.dto.make', function ($app) {
            return new DataTransferObjectMakeCommand($app['files']);
        });
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
