<?php

namespace JocelimJr\LaravelApiGenerator\Console;

use Exception;
use Illuminate\Console\ConfirmableTrait;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use JocelimJr\LaravelApiGenerator\Mappers\JsonDefinitionsMapper;
use JocelimJr\LaravelApiGenerator\Service\GeneratorService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use function Laravel\Prompts\multiselect;

class ApiGenerateCommand extends Command
{
    use ConfirmableTrait;

    protected $signature = 'api:generate';

    protected $description = 'Generate Files';

    protected GeneratorService $generatorService;

    public function __construct()
    {
        parent::__construct();

        $this->generatorService = app(GeneratorService::class);
    }

    /**
     * @throws FileNotFoundException
     * @throws Exception
     */
    public function handle(): void
    {
        $path = config('laravel-generator.path');

        $files = File::allFiles($path);

        $modules = [];

        foreach($files as $file){
            $modules[] = $file->getFilename();
        }

        $modulesToCreate = multiselect(
            label: 'Choose which module you want to create',
            options: $modules
        );

        foreach($modulesToCreate as $module){
            $jsonString = File::get(config('laravel-generator.path') . DIRECTORY_SEPARATOR . $module);

            $jsonDefinitionsDTO = JsonDefinitionsMapper::json2Class($jsonString);

            $this->generatorService->generate($jsonDefinitionsDTO);
        }
    }

}
