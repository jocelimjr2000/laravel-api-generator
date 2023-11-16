<?php

namespace JocelimJr\LaravelApiGenerator\Service;

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
     * @param mixed $obj
     * @return void
     * @throws Exception
     */
    public function generate(mixed $obj): void
    {
        $objGenDTO = $this->prepareReferences($obj);

        $this->check($objGenDTO);

        $createMigrationWriter = new CreateMigrationWriter($objGenDTO);
        $createModelWriter = new CreateModelWriter($objGenDTO);
        $createRepositoryWriter = new CreateRepositoryWriter($objGenDTO);
        $createMapperWriter = new CreateMapperWriter($objGenDTO);
        $createDTOWriter = new CreateDTOWriter($objGenDTO);
        $createServiceWriter = new CreateServiceWriter($objGenDTO);
        $createFormRequestWriter = new CreateFormRequestWriter($objGenDTO);
        $createControllerWriter = new CreateControllerWriter($objGenDTO);
        $createRoutesWriter = new CreateRoutesWriter($objGenDTO);
        $createFeatureTestWriter = new CreateFeatureTestWriter($objGenDTO);

        if($objGenDTO->isCreateMigration()) $createMigrationWriter->write();
        if($objGenDTO->isCreateModel()) $createModelWriter->write();
        if($objGenDTO->isRepositoryLayer() && $objGenDTO->isCreateRepository()) $createRepositoryWriter->write();
        if($objGenDTO->isMapperLayer() && $objGenDTO->isCreateMapper()) $createMapperWriter->write();
        if($objGenDTO->isDtoLayer() && $objGenDTO->isCreateDTO()) $createDTOWriter->write();
        if($objGenDTO->isServiceLayer() && $objGenDTO->isCreateService()) $createServiceWriter->write();
        if($objGenDTO->isCreateFormRequest()) $createFormRequestWriter->write();
        if($objGenDTO->isCreateController()) $createControllerWriter->write();
        if($objGenDTO->isCreateRoutes()) $createRoutesWriter->write();
        if($objGenDTO->isCreateFeatureTest()) $createFeatureTestWriter->write();

        $this->result($objGenDTO);
    }

    /**
     * @param ObjGenDTO $objGenDTO
     * @return void
     */
    private function check(ObjGenDTO $objGenDTO): void
    {
        $errors = [];

        if($objGenDTO->getApiName() == null) $errors[] = '> moduleName: Required';

        $usingAlias = false;

        if(count($objGenDTO->getColumns()) > 0){

            foreach($objGenDTO->getColumns() as $pos => $column){

                if(!isset($column->name)) $errors[] = '> columns[' . $pos . '].name: Required';

                if(isset($column->type)){
                    if(!in_array($column->type, DatabaseHelper::getAvailableColumns())) $errors[] = '> columns[' . $pos . '].type: Invalid column type \'' . $column->type . '\'';
                }else{
                    $errors[] = '> columns[' . $pos . '].type: Required';
                }

                if(!$usingAlias && isset($column->alias)){
                    $usingAlias = true;
                }
            }
        }else{
            $errors[] = '> columns: Required';
        }

        if($usingAlias && $objGenDTO->isDtoLayer() === false){
            $errors[] = '> To use "alias" in the columns it is necessary to enable the DTO layer ("dtoLayer")';
        }

        if($objGenDTO->isMapperLayer() === true && $objGenDTO->isDtoLayer() === false){
            $errors[] = '> To use "mapperLayer" it is necessary to enable the DTO layer ("dtoLayer")';
        }

        if(count($errors) > 0){
            Console::log($errors, 'black', true, 'red');
            die();
        }
    }

    /**
     * @param $obj
     * @return ObjGenDTO
     */
    private function prepareReferences($obj): ObjGenDTO
    {
        $objGenDTO = new ObjGenDTO();

        // Prepare Repository name
        if(isset($obj->repository)){
            $repository = $obj->repository;

            if(strtolower(substr($repository, -10)) !== 'repository'){
                $repository .= 'Repository';
            }
        }else{
            $repository = $obj->moduleName . 'Repository';
        }

        // Prepare Repository Interface name
        if(isset($obj->repositoryInterface)) {
            $repositoryInterface = $obj->repositoryInterface;

            if (strtolower(substr($repositoryInterface, -19)) !== 'repositoryInterface') {
                if (strtolower(substr($repositoryInterface, -10)) !== 'repository') {
                    $repositoryInterface .= 'RepositoryInterface';
                } else {
                    $repositoryInterface .= 'Interface';
                }
            }
        }else{
            $repositoryInterface = $obj->moduleName . 'RepositoryInterface';
        }

        // Prepare Mapper name
        if(isset($obj->mapper)) {
            $mapper = $obj->mapper;

            if(strtolower(substr($mapper, -6)) !== 'mapper') {
                $mapper .= 'Mapper';
            }
        }else{
            $mapper = $obj->moduleName . 'Mapper';
        }

        // Prepare DTO name
        if(isset($obj->dto)) {
            $dto = $obj->dto;

            if(strtolower(substr($dto, -3)) !== 'dto') {
                $dto .= 'DTO';
            }
        }else{
            $dto = $obj->moduleName . 'DTO';
        }

        // Prepare Service name
        if(isset($obj->service)) {
            $service = $obj->service;

            if(strtolower(substr($service, -7)) !== 'service') {
                $service .= 'Service';
            }
        }else{
            $service = $obj->moduleName . 'Service';
        }

        // Prepare Form Request name
        if(isset($obj->formRequest)) {
            $formRequest = $obj->formRequest;

            if(strtolower(substr($formRequest, -7)) !== 'request') {
                $formRequest .= 'Request';
            }
        }else{
            $formRequest = $obj->moduleName . 'Request';
        }

        // Prepare Controller name
        if(isset($obj->controller)) {
            $controller = $obj->controller;

            if(strtolower(substr($obj->controller, -10)) !== 'controller') {
                $controller .= 'Controller';
            }
        }else{
            $controller = $obj->moduleName . 'Controller';
        }

        // Prepare Feature Test name
        if(isset($obj->featureTest)) {
            $featureTest = $obj->featureTest;

            if(strtolower(substr($obj->featureTest, -4)) !== 'test') {
                $featureTest .= 'Test';
            }
        }else{
            $featureTest = $obj->moduleName . 'Test';
        }

        $idType = 'string';
        $idAlias = 'id';
        $idName = 'id';

        foreach($obj->columns as $column){
            if((isset($column->primary) && $column->primary === true) || $column->type == 'id'){
                $idType = ParametersHelper::getParameterType($column->type, false);
                $idAlias = ($column->alias ?? null);
                $idName = $column->name;
                break;
            }
        }

        if(isset($obj->tags)){
            if(is_array($obj->tags)) {
                $tags = $obj->tags;
            }else{
                $tags = [$obj->tags];
            }
        }else{
            $tags = [$obj->moduleName];
        }

        $objGenDTO->setApiName($obj->moduleName);
        $objGenDTO->setServiceLayer((isset($obj->serviceLayer) && is_bool($obj->serviceLayer)) ? $obj->serviceLayer : true);
        $objGenDTO->setRepositoryLayer((isset($obj->repositoryLayer) && is_bool($obj->repositoryLayer)) ? $obj->repositoryLayer : true);
        $objGenDTO->setMapperLayer((isset($obj->mapperLayer) && is_bool($obj->mapperLayer)) ? $obj->mapperLayer : true);
        $objGenDTO->setDtoLayer((isset($obj->dtoLayer) && is_bool($obj->dtoLayer)) ? $obj->dtoLayer : true);
        $objGenDTO->setCreateMigration((isset($obj->createMigration) && is_bool($obj->createMigration)) ? $obj->createMigration : true);
        $objGenDTO->setCreateModel((isset($obj->createModel) && is_bool($obj->createModel)) ? $obj->createModel : true);
        $objGenDTO->setCreateRepository((isset($obj->createRepository) && is_bool($obj->createRepository)) ? $obj->createRepository : true);
        $objGenDTO->setCreateMapper((isset($obj->createMapper) && is_bool($obj->createMapper)) ? $obj->createMapper : true);
        $objGenDTO->setCreateDTO((isset($obj->createDTO) && is_bool($obj->createDTO)) ? $obj->createDTO : true);
        $objGenDTO->setCreateService((isset($obj->createService) && is_bool($obj->createService)) ? $obj->createService : true);
        $objGenDTO->setCreateFormRequest((isset($obj->createFormRequest) && is_bool($obj->createFormRequest)) ? $obj->createFormRequest : true);
        $objGenDTO->setCreateController((isset($obj->createController) && is_bool($obj->createController)) ? $obj->createController : true);
        $objGenDTO->setCreateRoutes((isset($obj->createRoutes) && is_bool($obj->createRoutes)) ? $obj->createRoutes : true);
        $objGenDTO->setCreateFeatureTest((isset($obj->createFeatureTest) && is_bool($obj->createFeatureTest)) ? $obj->createFeatureTest : true);
        $objGenDTO->setWriteApiFindAll((isset($obj->writeApiFindAll) && is_bool($obj->writeApiFindAll)) ? $obj->writeApiFindAll : true);
        $objGenDTO->setWriteApiFindById((isset($obj->writeApiFindById) && is_bool($obj->writeApiFindById)) ? $obj->writeApiFindById : true);
        $objGenDTO->setWriteApiCreate((isset($obj->writeApiCreate) && is_bool($obj->writeApiCreate)) ? $obj->writeApiCreate : true);
        $objGenDTO->setWriteApiUpdate((isset($obj->writeApiUpdate) && is_bool($obj->writeApiUpdate)) ? $obj->writeApiUpdate : true);
        $objGenDTO->setWriteApiDelete((isset($obj->writeApiDelete) && is_bool($obj->writeApiDelete)) ? $obj->writeApiDelete : true);
        $objGenDTO->setApiPrefix($obj->apiPrefix ?? lcfirst($obj->moduleName));
        $objGenDTO->setPrefixDateMigration($obj->prefixDateMigration ?? date('Y_m_d_His'));
        $objGenDTO->setModel(ucfirst($obj->model ?? $obj->moduleName));
        $objGenDTO->setRepository(ucfirst($repository));
        $objGenDTO->setRepositoryInterface(ucfirst($repositoryInterface));
        $objGenDTO->setMapper(ucfirst($mapper));
        $objGenDTO->setDto(ucfirst($dto));
        $objGenDTO->setService(ucfirst($service));
        $objGenDTO->setFormRequest(ucfirst($formRequest));
        $objGenDTO->setFormCreateRequest(ucfirst($obj->formCreateRequest ?? $obj->moduleName . 'CreateRequest'));
        $objGenDTO->setFormUpdateRequest(ucfirst($obj->formUpdateRequest ?? $obj->moduleName . 'UpdateRequest'));
        $objGenDTO->setFormDeleteRequest(ucfirst($obj->formDeleteRequest ??$obj->moduleName . 'DeleteRequest' ));
        $objGenDTO->setController(ucfirst($controller));
        $objGenDTO->setFeatureTest(ucfirst($featureTest));
        $objGenDTO->setTable($obj->table ?? $obj->moduleName);
        $objGenDTO->setTags($tags);
        $objGenDTO->setColumns($obj->columns ?? []);
        $objGenDTO->setRoutesFileName('api-' . strtolower($obj->moduleName));
        $objGenDTO->setIdType($idType);
        $objGenDTO->setIdAlias($idAlias);
        $objGenDTO->setIdName($idName);

        $GLOBALS['LARAVEL_GENERATOR_PATH'] = isset($obj->path) ? (str_starts_with(DIRECTORY_SEPARATOR, $obj->path) ? $obj->path : DIRECTORY_SEPARATOR . $obj->path) : null;
        $GLOBALS['LARAVEL_GENERATOR_BASE_PATH'] = $obj->basePath ?? getcwd();

        return $objGenDTO;
    }

    private function result(ObjGenDTO $objGenDTO): void
    {
        $arr = [];
        $arr[] = '> Base Path';
        $arr[] = '>> ' . PathHelper::basePath();
        $arr[] = '';
        $arr[] = '> Created Files';

        foreach($objGenDTO->getCreatedFiles() as $createdFile){
            $arr[] = '>> '. $createdFile;
        }

        $arr[] = '';
        $arr[] = '> Modified Files';

        foreach($objGenDTO->getModifiedFiles() as $modifiedFile){
            $arr[] = '>> '. $modifiedFile;
        }

        $arr[] = '';
        $arr[] = '> Errors';

        foreach($objGenDTO->getErrors() as $error){
            $arr[] = '>> '. $error;
        }

        $arr[] = '';

        Console::log($arr, 'black', true, 'red');
    }
}
