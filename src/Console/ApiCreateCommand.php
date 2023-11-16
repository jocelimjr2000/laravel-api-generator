<?php

namespace JocelimJr\LaravelApiGenerator\Console;

use JocelimJr\LaravelApiGenerator\DataTransferObject\JsonDefinitionsDTO;
use Composer\InstalledVersions;
use Illuminate\Console\ConfirmableTrait;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Exception;
use Illuminate\Support\Facades\Validator;
use Nwidart\Modules\Facades\Module;

class ApiCreateCommand extends Command
{
    use ConfirmableTrait;

    protected $signature = 'api:create';

    protected $description = 'Create module';

    public function handle(): void
    {
        if (! $this->confirmToProceed()) {
            return;
        }

        $apiName = $this->askWithValidation('What should the API be named?', ['required'], ['required' => 'The apiName is required']);
        $table = $this->ask('What is the table name?', strtolower($apiName));
        $module = null;

        if (InstalledVersions::isInstalled(config('laravel-generator.packages.modules'))) {
            $modules = array_merge([null => 'none'], Module::all());
            $module = $this->choice('The package `' . config('laravel-generator.packages.modules') . '` has been detected in the project. Would you like to assign this API to any module?', $modules) ?: null;
        }

        $jsonFile = strtolower($apiName) . '.json';
        $file = config('laravel-generator.path') . DIRECTORY_SEPARATOR . $jsonFile;
        $create = true;

        if(File::exists($file)){
            $create = $this->confirm("The file `$jsonFile` already exists, do you want to overwrite it?");
        }

        if($create) {
            try {
                $jsonDefinitionsDTO = new JsonDefinitionsDTO();
                $jsonDefinitionsDTO->setApiName($apiName)
                                    ->setTable($table)
                                    ->setModule($module);

                if(!File::isDirectory(config('laravel-generator.path'))){
                    File::makeDirectory(config('laravel-generator.path'), 0777, true, true);
                }

                File::put($file, json_encode($jsonDefinitionsDTO, JSON_PRETTY_PRINT));

            } catch (Exception $e) {
                $this->error($e->getMessage());
            }
        }
    }

    public function askWithValidation(string $message, array $rules = [], array $messages = [], string $name = 'value') {
        $answer = $this->ask($message);
        $validator = Validator::make(
            [$name => $answer],
            [$name => $rules],
            $messages
        );

        if ($validator->passes()) {
            return $answer;
        }

        foreach ($validator->errors()->all() as $error) {
            $this->error($error);
        }

        return $this->askWithValidation($message, $rules, $messages, $name);
    }
}
