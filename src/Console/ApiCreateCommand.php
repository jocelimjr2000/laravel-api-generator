<?php

namespace JocelimJr\LaravelApiGenerator\Console;

use Composer\InstalledVersions;
use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Illuminate\Support\Facades\File;
use JocelimJr\LaravelApiGenerator\DataTransferObject\Column\ColumnId;
use JocelimJr\LaravelApiGenerator\DataTransferObject\Column\ColumnSoftDeletes;
use JocelimJr\LaravelApiGenerator\DataTransferObject\Column\ColumnTimestamp;
use JocelimJr\LaravelApiGenerator\DataTransferObject\Column\ColumnTimestamps;
use JocelimJr\LaravelApiGenerator\DataTransferObject\Column\ColumnUuid;
use JocelimJr\LaravelApiGenerator\DataTransferObject\JsonDefinitionsDTO;
use Nwidart\Modules\Facades\Module;
use function Laravel\Prompts\confirm;
use function Laravel\Prompts\multiselect;
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

        if(!$create) die();

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
                'auto-increment' => 'auto-increment',
                'uuid' => 'uuid',
            ],
        );

        $pkColumn = null;

        if($pk){
            if($pk == 'uuid'){
                $pkColumn = new ColumnUuid();
                $pkColumn->setName('id')
                            ->setPrimary(true);
            }else if($pk == 'auto-increment'){
                $pkColumn = new ColumnId();
            }
        }

        $refDates = multiselect(
            label: trans('laravel-generator::api-create.dateRef'),
            options: [
                'createdAt' => 'createdAt',
                'updatedAt' => 'updatedAt',
                'deletedAt' => 'deletedAt (soft-delete)',
            ],
            default: [
                'createdAt',
                'updatedAt'
            ]
        );

        $jsonDefinitionsDTO = new JsonDefinitionsDTO();
        $jsonDefinitionsDTO->setApiName($apiName)
                            ->setModule($module);

        if($pkColumn){
            $jsonDefinitionsDTO->addColumn($pkColumn);
        }

        if(in_array('createdAt', $refDates) && in_array('updatedAt', $refDates)){
            $createdAtAndUpdatedAt = new ColumnTimestamps();
            $jsonDefinitionsDTO->addColumn($createdAtAndUpdatedAt);
        }else {

            if (in_array('createdAt', $refDates)) {
                $createdAt = new ColumnTimestamp();
                $createdAt->setName('created_at')
                            ->setPrecision(0)
                            ->setNullable(true)
                            ->setCreatedAt(true);

                $jsonDefinitionsDTO->addColumn($createdAt);
            }

            if (in_array('updatedAt', $refDates)) {
                $updatedAt = new ColumnTimestamp();
                $updatedAt->setName('updated_at')
                            ->setPrecision(0)
                            ->setNullable(true)
                            ->setUpdatedAt(true);

                $jsonDefinitionsDTO->addColumn($updatedAt);
            }
        }

        if(in_array('deletedAt', $refDates)){
            $deletedAt = new ColumnSoftDeletes();

            $jsonDefinitionsDTO->addColumn($deletedAt);
        }

        $jsonDefinitionsDTO->loadDataByName();

        if(!File::isDirectory(config('laravel-generator.path'))){
            File::makeDirectory(config('laravel-generator.path'), 0777, true, true);
        }

        File::put($file, json_encode($jsonDefinitionsDTO, JSON_PRETTY_PRINT));
    }

}
