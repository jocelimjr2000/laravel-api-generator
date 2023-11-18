<?php

namespace JocelimJr\LaravelApiGenerator\Console;

use JocelimJr\LaravelApiGenerator\Classes\Column\ColumnId;
use JocelimJr\LaravelApiGenerator\DataTransferObject\ColumnDTO;
use JocelimJr\LaravelApiGenerator\DataTransferObject\JsonDefinitionsDTO;
use Composer\InstalledVersions;
use Illuminate\Console\ConfirmableTrait;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Exception;
use Nwidart\Modules\Facades\Module;
use function Laravel\Prompts\confirm;
use function Laravel\Prompts\select;
use function Laravel\Prompts\text;

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

        $apiName = text(
            label: trans('laravel-generator::api-create.apiName'),
            required: trans('laravel-generator::api-create.apiNameRequired')
        );

        $jsonFile = strtolower($apiName) . '.json';
        $file = config('laravel-generator.path') . DIRECTORY_SEPARATOR . $jsonFile;
        $create = true;

        if(File::exists($file)){
            $create = confirm(trans('laravel-generator::api-create.jsonFileExists', ['jsonFile' => $jsonFile]), false);
        }

        if($create) {
    //        $table = text(
    //            label: 'What is the table name?',
    //            default: strtolower($apiName)
    //        );

            $module = null;

            if (InstalledVersions::isInstalled(config('laravel-generator.packages.modules'))) {
                $modules = array_merge([null => 'none'], Module::all());

                $module = select(
                    label: trans('laravel-generator::api-create.modulesPackage', ['package' => config('laravel-generator.packages.modules')]),
                    options: $modules,
                ) ?: null;
            }

            $pk = select(
                label: trans('laravel-generator::api-create.choosePk'),
                options: [
                    'null' => 'none',
                    'auto-increment',
                    'uuid',
                ],
            ) ?: null;

            try {
                $pkColumn = new ColumnId();
                $pkColumn->setName('id');

                $jsonDefinitionsDTO = new JsonDefinitionsDTO();
                $jsonDefinitionsDTO->setApiName($apiName)
                                    ->setModule($module)
                                    ->setColumns([$pkColumn])
                                    ->loadDataByName();

                if(!File::isDirectory(config('laravel-generator.path'))){
                    File::makeDirectory(config('laravel-generator.path'), 0777, true, true);
                }

                File::put($file, json_encode($jsonDefinitionsDTO, JSON_PRETTY_PRINT));

            } catch (Exception $e) {
                $this->error($e->getMessage());
            }
        }
    }

}
