<?php

namespace JocelimJr\LaravelApiGenerator\Console;

use JocelimJr\LaravelApiGenerator\Service\GeneratorService;
use Illuminate\Console\GeneratorCommand;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\File;

class ApiGenerateCommand extends GeneratorCommand
{
    protected $name = 'api:generate';

    protected static $defaultName = 'api:generate';

    protected $description = 'Create basic api';

    protected $type = 'Api';

    protected GeneratorService $generatorService;

    public function __construct(Filesystem $files)
    {
        parent::__construct($files);

        $this->generatorService = app(GeneratorService::class);
    }

    public function handle()
    {
        $path = storage_path('api');

        if(!File::exists($path)) return null;

        $files = File::allFiles($path);

        foreach($files as $file){
            $this->generatorService->generate(json_decode($file->getContents()));
        }
    }

    protected function getStub()
    {
        return null;
    }

    protected function getArguments()
    {
        return [];
    }

}
