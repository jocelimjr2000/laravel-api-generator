<?php

namespace JocelimJr\LaravelApiGenerator\Service;

use JocelimJr\LaravelApiGenerator\DataTransferObject\ColumnDTO;
use JocelimJr\LaravelApiGenerator\DataTransferObject\JsonDefinitionsDTO;
use JocelimJr\LaravelApiGenerator\DataTransferObject\ObjGenDTO;
use JocelimJr\LaravelApiGenerator\Helpers\Console;
use JocelimJr\LaravelApiGenerator\Helpers\DatabaseHelper;
use JocelimJr\LaravelApiGenerator\Helpers\ParametersHelper;
use JocelimJr\LaravelApiGenerator\Helpers\PathHelper;
use JocelimJr\LaravelApiGenerator\Writers\CreateControllerWriter;
use JocelimJr\LaravelApiGenerator\Writers\CreateDTOWriter;
use JocelimJr\LaravelApiGenerator\Writers\CreateFeatureTestWriter;
use JocelimJr\LaravelApiGenerator\Writers\CreateFormRequestWriter;
use JocelimJr\LaravelApiGenerator\Writers\CreateMapperWriter;
use JocelimJr\LaravelApiGenerator\Writers\CreateMigrationWriter;
use JocelimJr\LaravelApiGenerator\Writers\CreateModelWriter;
use JocelimJr\LaravelApiGenerator\Writers\CreateRepositoryWriter;
use JocelimJr\LaravelApiGenerator\Writers\CreateRoutesWriter;
use JocelimJr\LaravelApiGenerator\Writers\CreateServiceWriter;
use Exception;

class GeneratorService
{

    /**
     * @param JsonDefinitionsDTO $jsonDefinitionsDTO
     * @return void
     * @throws Exception
     */
    public function generate(JsonDefinitionsDTO $jsonDefinitionsDTO): void
    {
//        $this->check($jsonDefinitionsDTO);

//        $createRepositoryWriter = new CreateRepositoryWriter($objGenDTO);
//        $createMapperWriter = new CreateMapperWriter($objGenDTO);
//        $createDTOWriter = new CreateDTOWriter($objGenDTO);
//        $createServiceWriter = new CreateServiceWriter($objGenDTO);
//        $createFormRequestWriter = new CreateFormRequestWriter($objGenDTO);
//        $createControllerWriter = new CreateControllerWriter($objGenDTO);
//        $createRoutesWriter = new CreateRoutesWriter($objGenDTO);
//        $createFeatureTestWriter = new CreateFeatureTestWriter($objGenDTO);

        if($jsonDefinitionsDTO->getCreateFile()->isMigration()){
            $migrationWriter = new CreateMigrationWriter($jsonDefinitionsDTO);
            $migrationWriter->write();
        }

        if($jsonDefinitionsDTO->getCreateFile()->isModel()){
            $modelWriter = new CreateModelWriter($jsonDefinitionsDTO);
            $modelWriter->write();
        }

        if($jsonDefinitionsDTO->getCreateFile()->isDto()){
            $modelWriter = new CreateDTOWriter($jsonDefinitionsDTO);
            $modelWriter->write();
        }

//        if($objGenDTO->isRepositoryLayer() && $objGenDTO->isCreateRepository()) $createRepositoryWriter->write();
//        if($objGenDTO->isMapperLayer() && $objGenDTO->isCreateMapper()) $createMapperWriter->write();
//        if($objGenDTO->isDtoLayer() && $objGenDTO->isCreateDTO()) $createDTOWriter->write();
//        if($objGenDTO->isServiceLayer() && $objGenDTO->isCreateService()) $createServiceWriter->write();
//        if($objGenDTO->isCreateFormRequest()) $createFormRequestWriter->write();
//        if($objGenDTO->isCreateController()) $createControllerWriter->write();
//        if($objGenDTO->isCreateRoutes()) $createRoutesWriter->write();
//        if($objGenDTO->isCreateFeatureTest()) $createFeatureTestWriter->write();

//        $this->result($objGenDTO);
    }

    /**
     * @param JsonDefinitionsDTO $objGenDTO
     * @return void
     */
//    private function check(JsonDefinitionsDTO $objGenDTO): void
//    {
//        $errors = [];
//
//        if($objGenDTO->getApiName() == null) $errors[] = '> moduleName: Required';
//
//        $usingAlias = false;
//
//        if(count($objGenDTO->getColumns()) > 0){
//
//            /**
//             * @var int $pos
//             * @var ColumnDTO $column
//             */
//            foreach($objGenDTO->getColumns() as $pos => $column){
//                if($column->getName() == null) $errors[] = '> columns[' . $pos . '].name: Required';
//
//                if($column->getType() !== null){
//                    if(!in_array($column->getType(), DatabaseHelper::getAvailableColumns())) $errors[] = '> columns[' . $pos . '].type: Invalid column type \'' . $column->getType() . '\'';
//                }else{
//                    $errors[] = '> columns[' . $pos . '].type: Required';
//                }
//
//                if(!$usingAlias && $column->getAlias() !== null){
//                    $usingAlias = true;
//                }
//            }
//        }else{
//            $errors[] = '> columns: Required';
//        }
//
//        if($usingAlias && $objGenDTO->getArchitecture()->isDto() === false){
//            $errors[] = '> To use "alias" in the columns it is necessary to enable the DTO layer ("dtoLayer")';
//        }
//
//        if($objGenDTO->getArchitecture()->isMapper() === true && $objGenDTO->getArchitecture()->isDto() === false){
//            $errors[] = '> To use "mapperLayer" it is necessary to enable the DTO layer ("dtoLayer")';
//        }
//
//        if(count($errors) > 0){
//            Console::log($errors, 'black', true, 'red');
//            die();
//        }
//    }
//
//    private function result(ObjGenDTO $objGenDTO): void
//    {
//        $arr = [];
//        $arr[] = '> Base Path';
//        $arr[] = '>> ' . PathHelper::basePath();
//        $arr[] = '';
//        $arr[] = '> Created Files';
//
//        foreach($objGenDTO->getCreatedFiles() as $createdFile){
//            $arr[] = '>> '. $createdFile;
//        }
//
//        $arr[] = '';
//        $arr[] = '> Modified Files';
//
//        foreach($objGenDTO->getModifiedFiles() as $modifiedFile){
//            $arr[] = '>> '. $modifiedFile;
//        }
//
//        $arr[] = '';
//        $arr[] = '> Errors';
//
//        foreach($objGenDTO->getErrors() as $error){
//            $arr[] = '>> '. $error;
//        }
//
//        $arr[] = '';
//
//        Console::log($arr, 'black', true, 'red');
//    }
}
