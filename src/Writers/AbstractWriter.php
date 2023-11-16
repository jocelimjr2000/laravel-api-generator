<?php

namespace JocelimJr\LaravelApiGenerator\Writers;

use JocelimJr\LaravelApiGenerator\DataTransferObject\ObjGenDTO;
use JocelimJr\LaravelApiGenerator\Helpers\Console;
use JocelimJr\LaravelApiGenerator\Helpers\ParametersHelper;
use JocelimJr\LaravelApiGenerator\Helpers\PathHelper;

abstract class AbstractWriter
{
    protected ?ObjGenDTO $objGenDTO = null;
    private array $allParametersToReplace = [];
    private array $replaceImports = [];
    private string $replaceConstruct = '';
    private string $replaceParameters = '';
    private string $replaceMethods = '';

    protected function addExtraReplaceParametersToReplace(array $parameters): void
    {
        foreach($parameters as $k => $v){
            $this->allParametersToReplace[$k] = $v;
        }
    }

    protected function addReplaceImports(string|array $import): void
    {
        if(is_array($import)){
            foreach ($import as $i){
                $this->replaceImports[] = $i;
            }
        }else{
            $this->replaceImports[] = $import;
        }

        $this->updateAllParametersToReplace();
    }

    protected function setReplaceImports(array $replaceImports): void
    {
        $this->replaceImports = $replaceImports;
        $this->updateAllParametersToReplace();
    }

    protected function setReplaceConstruct(string $replaceConstruct): void
    {
        $this->replaceConstruct = $replaceConstruct;
        $this->updateAllParametersToReplace();
    }

    protected function setReplaceParameters(string $replaceParameters): void
    {
        $this->replaceParameters = $replaceParameters;
        $this->updateAllParametersToReplace();
    }

    protected function setReplaceMethods(string $replaceMethods): void
    {
        $this->replaceMethods = $replaceMethods;
        $this->updateAllParametersToReplace();
    }

    public function __construct(?ObjGenDTO $objGenDTO = null)
    {
        $this->objGenDTO = $objGenDTO;

        $this->updateAllParametersToReplace();
    }

    private function updateAllParametersToReplace(): void
    {
        if($this->objGenDTO !== null) {
            $this->allParametersToReplace = [
                '{{ modelSettersValues }}' => $this->createSetterValueModelColumns(),
                '{{ modelSettersValuesDTO }}' => $this->createSetterValueModelColumns(true),
                '{{ arrayForModel }}' => $this->createArrayForModel(),
                '{{ imports }}' => $this->loadImports(),
                '{{ controller }}' => $this->objGenDTO->getController(),
                '{{ construct }}' => $this->replaceConstruct,
                '{{ parameters }}' => $this->replaceParameters,
                '{{ methods }}' => $this->replaceMethods,
                '{{ dto }}' => $this->objGenDTO->getDto(),
                '{{ dtoVar }}' => $this->objGenDTO->getDto(true),
                '{{ dtoSettersValues }}' => $this->createDTOSettersValues($this->objGenDTO->getModel(true)),
                '{{ formCreateRequest }}' => $this->objGenDTO->getFormCreateRequest(),
                '{{ formUpdateRequest }}' => $this->objGenDTO->getFormUpdateRequest(),
                '{{ formDeleteRequest }}' => $this->objGenDTO->getFormDeleteRequest(),
                '{{ idParameter }}' => $this->objGenDTO->getIdParameterMode(),
                '{{ idVar }}' => $this->objGenDTO->getIdVariableMode(),
                '{{ idName }}' => $this->objGenDTO->getIdName(),
                '{{ idGetter }}' => $this->objGenDTO->getIdGetterMode(),
                '{{ mapper }}' => $this->objGenDTO->getMapper(),
                '{{ model }}' => $this->objGenDTO->getModel(),
                '{{ modelVar }}' => $this->objGenDTO->getModel(true),
                '{{ moduleName }}' => $this->objGenDTO->getApiName(),
                '{{ prefix }}' => $this->objGenDTO->getApiPrefix(),
                '{{ repository }}' => $this->objGenDTO->getRepository(),
                '{{ repositoryVar }}' => $this->objGenDTO->getRepositoryVariableMode(),
                '{{ repositoryParameter }}' => $this->objGenDTO->getRepositoryParameterMode(),
                '{{ repositoryInterface }}' => $this->objGenDTO->getRepositoryInterface(),
                '{{ service }}' => $this->objGenDTO->getService(),
                '{{ serviceVar }}' => $this->objGenDTO->getServiceVariableMode(),
                '{{ serviceParameter }}' => $this->objGenDTO->getServiceParameterMode(),
                '{{ serviceOrRepository }}' => lcfirst($this->objGenDTO->isServiceLayer() ? $this->objGenDTO->getService() : $this->objGenDTO->getRepository()),
                '{{ table }}' => $this->objGenDTO->getTable(),
                '{{ test }}' => $this->objGenDTO->getFeatureTest(),
                '{{ typeId }}' => $this->objGenDTO->getIdType(),
            ];
        }
    }

    /**
     * @return string
     */
    protected function loadImports(): string
    {
        $str = '';

        foreach($this->replaceImports as $k => $v){
            $str .= 'use ' . $v . ';' . (($k < count($this->replaceImports) - 1) ? PHP_EOL : '');
        }

        return $str;
    }

    /**
     * @param array $list
     * @param string $prefix
     * @param string $suffix
     * @param bool $breakLastLine
     * @return string
     */
    protected function loadList(array $list, string $prefix = '', string $suffix = ';', bool $breakLastLine = false): string
    {
        $str = '';

        foreach($list as $k => $v){
            $str .= $prefix . $v . $suffix . (($k < count($list) - 1) ? PHP_EOL : '');
        }

        if($breakLastLine) {
            $str .= PHP_EOL;
        }

        return $str;
    }

    /**
     * @param string $content
     * @return string
     */
    protected function replaceParameters(string $content): string
    {
        foreach($this->allParametersToReplace as $k => $v){
            $content = str_replace($k, $v, $content);
        }

        $allParameters = ParametersHelper::getBetween($content, "{{ ", " }}");

        if(count($allParameters) > 0){
            $paramsNotFound = [];

            foreach($allParameters as $p){
                $p = "{{ $p }}";

                if(!isset($this->allParametersToReplace[$p])){
                    $paramsNotFound[] = $p;
                }
            }

            if(count($paramsNotFound) > 0){
                Console::log(array_merge(['-- Invalid parameters --'], $paramsNotFound), 'black', true, 'red');
                die();
            }

            return $this->replaceParameters($content);
        }

        $this->clear();

        return $content;
    }

    private function clear(): void
    {
        $this->allParametersToReplace = [];
        $this->replaceImports = [];
        $this->replaceConstruct = '';
        $this->replaceParameters = '';
        $this->replaceMethods = '';
        $this->updateAllParametersToReplace();
    }

    protected function writeFile(array $createFile, string $content, string $ext = '.php'): void
    {
        $file = end($createFile);
        array_pop($createFile);
        $folder = $createFile;

        if(!str_ends_with($file, $ext)) $file .= $ext;

        if(!is_dir(PathHelper::basePath($folder))) {
            mkdir(PathHelper::basePath($folder), 0777, true);
        }

        $newFile = PathHelper::basePath(array_merge($folder, [$file]));

        $saved_file = file_put_contents($newFile, $content);

        if (!($saved_file === false || $saved_file == -1)) {
            $this->objGenDTO->addCreatedFile($newFile);
        }else{
            $this->objGenDTO->addError('Error creating file: ' . $newFile);
        }
    }

    private function createDTOSettersValues(string $parameterModel): string
    {
        $columns = '';
        foreach ($this->objGenDTO->getColumns() as $column) {
            $_param = ($column->alias ?? $column->name);
            $columns .= '        {{ dtoVar }}->set' . ucfirst($_param) . '(' . $parameterModel . '->' . $column->name . ');' . PHP_EOL ;
        }

        return $columns;
    }

    private function createArrayForModel(): string
    {
        $databaseColumns = '';
        foreach ($this->objGenDTO->getColumns() as $column) {
            if(
                $column->type == 'id' ||
                (isset($column->primary) && $column->primary === true) ||
                (isset($column->createdAt) && $column->createdAt === true) ||
                (isset($column->updatedAt) && $column->updatedAt === true) ||
                (isset($column->deletedAt) && $column->deletedAt === true)
            ) continue;

            $databaseColumns .= '            \'' . $column->name . '\' => $request->' . ($column->alias ?? $column->name) . ',' . PHP_EOL ;
        }

        return $databaseColumns;
    }

    private function createSetterValueModelColumns(bool $dtoMode = false): string
    {
        $columns = '';
        foreach ($this->objGenDTO->getColumns() as $column) {
            if(
                $column->type == 'id' ||
                (isset($column->primary) && $column->primary === true) ||
                (isset($column->createdAt) && $column->createdAt === true) ||
                (isset($column->updatedAt) && $column->updatedAt === true)
            ) continue;

            if($dtoMode){
                $_value = '{{ dtoVar }}->get' . (ucfirst($column->alias) ?? $column->name) . '();';
            }else{
                $_value = '$request->' . ($column->alias ?? $column->name);
            }

            $columns .= '        {{ modelVar }}->' . $column->name . ' = ' . $_value . ';' . PHP_EOL ;
        }

        return $columns;
    }
}
