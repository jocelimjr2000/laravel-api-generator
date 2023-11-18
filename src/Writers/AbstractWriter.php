<?php

namespace JocelimJr\LaravelApiGenerator\Writers;

use JocelimJr\LaravelApiGenerator\DataTransferObject\JsonDefinitionsDTO;
use JocelimJr\LaravelApiGenerator\Helpers\FileHelper;
use JocelimJr\LaravelApiGenerator\Helpers\ParametersHelper;
use JocelimJr\LaravelApiGenerator\Helpers\PathHelper;

abstract class AbstractWriter extends AbstractReplace
{
    public function __construct(JsonDefinitionsDTO $jsonDefinitionsDTO)
    {
        parent::__construct($jsonDefinitionsDTO);
    }

    abstract function write(): void;

    protected function addImportsToReplace(string|array $import): void
    {
        if(is_array($import)){
            foreach ($import as $i){
                $this->importsToReplace[] = $i;
            }
        }else{
            $this->importsToReplace[] = $import;
        }
    }

//    private function refreshParametersToReplace(): void
//    {
//        if($this->jsonDefinitionsDTO !== null) {
//            $this->parametersToReplace = [
//                'imports' => $this->importsToStr(),
//                'model' => $this->jsonDefinitionsDTO->getFileName()->getModel(),
//                'modelVar' => $this->jsonDefinitionsDTO->getFileName()->getModel(true),
//                'primaryKey' => $this->jsonDefinitionsDTO->getPrimaryKey()->getName(),
//                'table' => $this->jsonDefinitionsDTO->getTable(),
//
////                '{{ migrationColumns }}' => $this->jsonDefinitionsDTO->getTable(),
////                '{{ modelSettersValues }}' => $this->createSetterValueModelColumns(),
////                '{{ modelSettersValuesDTO }}' => $this->createSetterValueModelColumns(true),
////                '{{ arrayForModel }}' => $this->createArrayForModel(),
////                '{{ imports }}' => $this->loadImports(),
////                '{{ controller }}' => $this->jsonDefinitionsDTO->getFileName()->getController(),
////                '{{ construct }}' => $this->replaceConstruct,
////                '{{ parameters }}' => $this->replaceParameters,
////                '{{ methods }}' => $this->replaceMethods,
////                '{{ dto }}' => $this->jsonDefinitionsDTO->getFileName()->getDto(),
////                '{{ dtoVar }}' => $this->jsonDefinitionsDTO->getFileName()->getDto(true),
////                '{{ dtoSettersValues }}' => $this->createDTOSettersValues($this->jsonDefinitionsDTO->getFileName()->getModel(true)),
////                '{{ formCreateRequest }}' => $this->jsonDefinitionsDTO->getFormCreateRequest(),
////                '{{ formUpdateRequest }}' => $this->jsonDefinitionsDTO->getFormUpdateRequest(),
////                '{{ formDeleteRequest }}' => $this->jsonDefinitionsDTO->getFormDeleteRequest(),
////                '{{ idParameter }}' => $this->jsonDefinitionsDTO->getIdParameterMode(),
////                '{{ idVar }}' => $this->jsonDefinitionsDTO->getIdVariableMode(),
////                '{{ idName }}' => $this->jsonDefinitionsDTO->getIdName(),
////                '{{ idGetter }}' => $this->jsonDefinitionsDTO->getIdGetterMode(),
////                '{{ mapper }}' => $this->jsonDefinitionsDTO->getMapper(),
////                '{{ moduleName }}' => $this->jsonDefinitionsDTO->getApiName(),
////                '{{ prefix }}' => $this->jsonDefinitionsDTO->getApiPrefix(),
////                '{{ repository }}' => $this->jsonDefinitionsDTO->getRepository(),
////                '{{ repositoryVar }}' => $this->jsonDefinitionsDTO->getRepositoryVariableMode(),
////                '{{ repositoryParameter }}' => $this->jsonDefinitionsDTO->getRepositoryParameterMode(),
////                '{{ repositoryInterface }}' => $this->jsonDefinitionsDTO->getRepositoryInterface(),
////                '{{ service }}' => $this->jsonDefinitionsDTO->getService(),
////                '{{ serviceVar }}' => $this->jsonDefinitionsDTO->getServiceVariableMode(),
////                '{{ serviceParameter }}' => $this->jsonDefinitionsDTO->getServiceParameterMode(),
////                '{{ serviceOrRepository }}' => lcfirst($this->jsonDefinitionsDTO->isServiceLayer() ? $this->jsonDefinitionsDTO->getService() : $this->jsonDefinitionsDTO->getRepository()),
////                '{{ table }}' => $this->jsonDefinitionsDTO->getTable(),
////                '{{ test }}' => $this->jsonDefinitionsDTO->getFeatureTest(),
////                '{{ typeId }}' => $this->jsonDefinitionsDTO->getIdType(),
//            ];
//        }
//    }

    protected function replaceStubParameters(string $stub): string
    {
        $content = FileHelper::getStubPath($stub);

        return $this->replaceParameters($content);
    }

    protected function replaceParameters(string $content): string
    {
        $allParameters = ParametersHelper::getBetween($content, "{{ ", " }}");

        if(count($allParameters) > 0){
            foreach($allParameters as $p){
                $newValue = $this->$p();
                $content = str_replace("{{ $p }}", $newValue, $content);
            }

            return $this->replaceParameters($content);
        }

        return $content;
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

//        if (!($saved_file === false || $saved_file == -1)) {
//            $this->jsonDefinitionsDTO->addCreatedFile($newFile);
//        }else{
//            $this->jsonDefinitionsDTO->addError('Error creating file: ' . $newFile);
//        }
    }

}
