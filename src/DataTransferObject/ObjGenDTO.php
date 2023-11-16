<?php

namespace JocelimJr\LaravelApiGenerator\DataTransferObject;

use JocelimJr\LaravelApiGenerator\Helpers\PathHelper;

class ObjGenDTO
{
    private ?string $apiName = null;
    private bool $serviceLayer = true;
    private bool $repositoryLayer = true;
    private bool $mapperLayer = true;
    private bool $dtoLayer = true;
    private bool $createMigration = true;
    private bool $createModel = true;
    private bool $createRepository = true;
    private bool $createMapper = true;
    private bool $createDTO = true;
    private bool $createService = true;
    private bool $createFormRequest = true;
    private bool $createController = true;
    private bool $createRoutes = true;
    private bool $createFeatureTest = true;
    private bool $writeApiFindAll = true;
    private bool $writeApiFindById = true;
    private bool $writeApiCreate = true;
    private bool $writeApiUpdate = true;
    private bool $writeApiDelete = true;
    private ?string $apiPrefix = null;
    private ?string $prefixDateMigration = null;
    private ?string $model = null;
    private ?string $repository = null;
    private ?string $repositoryInterface = null;
    private ?string $mapper = null;
    private ?string $dto = null;
    private ?string $service = null;
    private ?string $formRequest = null;
    private ?string $formCreateRequest = null;
    private ?string $formUpdateRequest = null;
    private ?string $formDeleteRequest = null;
    private ?string $controller = null;
    private ?string $featureTest = null;
    private ?string $table = null;
    private array $tags = [];
    private array $columns = [];
    private ?string $routesFileName = null;
    private ?string $idType = null;
    private ?string $idAlias = null;
    private ?string $idName = null;
    private array $createdFiles = [];
    private array $modifiedFiles = [];
    private array $errors = [];

    /**
     * @return string|null
     */
    public function getApiName(): ?string
    {
        return $this->apiName;
    }

    /**
     * @param string|null $apiName
     * @return ObjGenDTO
     */
    public function setApiName(?string $apiName): ObjGenDTO
    {
        $this->apiName = $apiName;
        return $this;
    }

    /**
     * @return bool
     */
    public function isServiceLayer(): bool
    {
        return $this->serviceLayer;
    }

    /**
     * @param bool $serviceLayer
     * @return ObjGenDTO
     */
    public function setServiceLayer(bool $serviceLayer): ObjGenDTO
    {
        $this->serviceLayer = $serviceLayer;
        return $this;
    }

    /**
     * @return bool
     */
    public function isRepositoryLayer(): bool
    {
        return $this->repositoryLayer;
    }

    /**
     * @param bool $repositoryLayer
     * @return ObjGenDTO
     */
    public function setRepositoryLayer(bool $repositoryLayer): ObjGenDTO
    {
        $this->repositoryLayer = $repositoryLayer;
        return $this;
    }

    /**
     * @return bool
     */
    public function isMapperLayer(): bool
    {
        return $this->mapperLayer;
    }

    /**
     * @param bool $mapperLayer
     * @return ObjGenDTO
     */
    public function setMapperLayer(bool $mapperLayer): ObjGenDTO
    {
        $this->mapperLayer = $mapperLayer;
        return $this;
    }

    /**
     * @return bool
     */
    public function isDtoLayer(): bool
    {
        return $this->dtoLayer;
    }

    /**
     * @param bool $dtoLayer
     * @return ObjGenDTO
     */
    public function setDtoLayer(bool $dtoLayer): ObjGenDTO
    {
        $this->dtoLayer = $dtoLayer;
        return $this;
    }

    /**
     * @return bool
     */
    public function isCreateMigration(): bool
    {
        return $this->createMigration;
    }

    /**
     * @param bool $createMigration
     * @return ObjGenDTO
     */
    public function setCreateMigration(bool $createMigration): ObjGenDTO
    {
        $this->createMigration = $createMigration;
        return $this;
    }

    /**
     * @return bool
     */
    public function isCreateModel(): bool
    {
        return $this->createModel;
    }

    /**
     * @param bool $createModel
     * @return ObjGenDTO
     */
    public function setCreateModel(bool $createModel): ObjGenDTO
    {
        $this->createModel = $createModel;
        return $this;
    }

    /**
     * @return bool
     */
    public function isCreateRepository(): bool
    {
        return $this->createRepository;
    }

    /**
     * @param bool $createRepository
     * @return ObjGenDTO
     */
    public function setCreateRepository(bool $createRepository): ObjGenDTO
    {
        $this->createRepository = $createRepository;
        return $this;
    }

    /**
     * @return bool
     */
    public function isCreateMapper(): bool
    {
        return $this->createMapper;
    }

    /**
     * @param bool $createMapper
     * @return ObjGenDTO
     */
    public function setCreateMapper(bool $createMapper): ObjGenDTO
    {
        $this->createMapper = $createMapper;
        return $this;
    }

    /**
     * @return bool
     */
    public function isCreateDTO(): bool
    {
        return $this->createDTO;
    }

    /**
     * @param bool $createDTO
     * @return ObjGenDTO
     */
    public function setCreateDTO(bool $createDTO): ObjGenDTO
    {
        $this->createDTO = $createDTO;
        return $this;
    }

    /**
     * @return bool
     */
    public function isCreateService(): bool
    {
        return $this->createService;
    }

    /**
     * @param bool $createService
     * @return ObjGenDTO
     */
    public function setCreateService(bool $createService): ObjGenDTO
    {
        $this->createService = $createService;
        return $this;
    }

    /**
     * @return bool
     */
    public function isCreateFormRequest(): bool
    {
        return $this->createFormRequest;
    }

    /**
     * @param bool $createFormRequest
     * @return ObjGenDTO
     */
    public function setCreateFormRequest(bool $createFormRequest): ObjGenDTO
    {
        $this->createFormRequest = $createFormRequest;
        return $this;
    }

    /**
     * @return bool
     */
    public function isCreateController(): bool
    {
        return $this->createController;
    }

    /**
     * @param bool $createController
     * @return ObjGenDTO
     */
    public function setCreateController(bool $createController): ObjGenDTO
    {
        $this->createController = $createController;
        return $this;
    }

    /**
     * @return bool
     */
    public function isCreateRoutes(): bool
    {
        return $this->createRoutes;
    }

    /**
     * @param bool $createRoutes
     * @return ObjGenDTO
     */
    public function setCreateRoutes(bool $createRoutes): ObjGenDTO
    {
        $this->createRoutes = $createRoutes;
        return $this;
    }

    /**
     * @return bool
     */
    public function isCreateFeatureTest(): bool
    {
        return $this->createFeatureTest;
    }

    /**
     * @param bool $createFeatureTest
     * @return ObjGenDTO
     */
    public function setCreateFeatureTest(bool $createFeatureTest): ObjGenDTO
    {
        $this->createFeatureTest = $createFeatureTest;
        return $this;
    }

    /**
     * @return bool
     */
    public function isWriteApiFindAll(): bool
    {
        return $this->writeApiFindAll;
    }

    /**
     * @param bool $writeApiFindAll
     * @return ObjGenDTO
     */
    public function setWriteApiFindAll(bool $writeApiFindAll): ObjGenDTO
    {
        $this->writeApiFindAll = $writeApiFindAll;
        return $this;
    }

    /**
     * @return bool
     */
    public function isWriteApiFindById(): bool
    {
        return $this->writeApiFindById;
    }

    /**
     * @param bool $writeApiFindById
     * @return ObjGenDTO
     */
    public function setWriteApiFindById(bool $writeApiFindById): ObjGenDTO
    {
        $this->writeApiFindById = $writeApiFindById;
        return $this;
    }

    /**
     * @return bool
     */
    public function isWriteApiCreate(): bool
    {
        return $this->writeApiCreate;
    }

    /**
     * @param bool $writeApiCreate
     * @return ObjGenDTO
     */
    public function setWriteApiCreate(bool $writeApiCreate): ObjGenDTO
    {
        $this->writeApiCreate = $writeApiCreate;
        return $this;
    }

    /**
     * @return bool
     */
    public function isWriteApiUpdate(): bool
    {
        return $this->writeApiUpdate;
    }

    /**
     * @param bool $writeApiUpdate
     * @return ObjGenDTO
     */
    public function setWriteApiUpdate(bool $writeApiUpdate): ObjGenDTO
    {
        $this->writeApiUpdate = $writeApiUpdate;
        return $this;
    }

    /**
     * @return bool
     */
    public function isWriteApiDelete(): bool
    {
        return $this->writeApiDelete;
    }

    /**
     * @param bool $writeApiDelete
     * @return ObjGenDTO
     */
    public function setWriteApiDelete(bool $writeApiDelete): ObjGenDTO
    {
        $this->writeApiDelete = $writeApiDelete;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getApiPrefix(): ?string
    {
        return $this->apiPrefix;
    }

    /**
     * @param string|null $apiPrefix
     * @return ObjGenDTO
     */
    public function setApiPrefix(?string $apiPrefix): ObjGenDTO
    {
        $this->apiPrefix = $apiPrefix;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPrefixDateMigration(): ?string
    {
        return $this->prefixDateMigration;
    }

    /**
     * @param string|null $prefixDateMigration
     * @return ObjGenDTO
     */
    public function setPrefixDateMigration(?string $prefixDateMigration): ObjGenDTO
    {
        $this->prefixDateMigration = $prefixDateMigration;
        return $this;
    }

    /**
     * @param bool $variableMode
     * @return string|null
     */
    public function getModel(bool $variableMode = false): ?string
    {
        if($variableMode) return '$' . lcfirst($this->model);

        return $this->model;
    }

    /**
     * @param string|null $model
     * @return ObjGenDTO
     */
    public function setModel(?string $model): ObjGenDTO
    {
        $this->model = $model;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getRepository(): ?string
    {
        return $this->repository;
    }

    /**
     * @return string|null
     */
    public function getRepositoryParameterMode(): ?string
    {
        return lcfirst($this->repository);
    }

    /**
     * @return string|null
     */
    public function getRepositoryVariableMode(): ?string
    {
        return '$'. lcfirst($this->repository);
    }

    /**
     * @param string|null $repository
     * @return ObjGenDTO
     */
    public function setRepository(?string $repository): ObjGenDTO
    {
        $this->repository = $repository;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getRepositoryInterface(): ?string
    {
        return $this->repositoryInterface;
    }

    /**
     * @param string|null $repositoryInterface
     * @return ObjGenDTO
     */
    public function setRepositoryInterface(?string $repositoryInterface): ObjGenDTO
    {
        $this->repositoryInterface = $repositoryInterface;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getMapper(): ?string
    {
        return $this->mapper;
    }

    /**
     * @param string|null $mapper
     * @return ObjGenDTO
     */
    public function setMapper(?string $mapper): ObjGenDTO
    {
        $this->mapper = $mapper;
        return $this;
    }

    /**
     * @param bool $variableMode
     * @return string|null
     */
    public function getDto(bool $variableMode = false): ?string
    {
        if($variableMode) return '$' . lcfirst($this->dto);

        return $this->dto;
    }

    /**
     * @param string|null $dto
     * @return ObjGenDTO
     */
    public function setDto(?string $dto): ObjGenDTO
    {
        $this->dto = $dto;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getService(): ?string
    {
        return $this->service;
    }

    /**
     * @return string|null
     */
    public function getServiceVariableMode(): ?string
    {
        return '$'. lcfirst($this->service);
    }

    /**
     * @return string|null
     */
    public function getServiceParameterMode(): ?string
    {
        return lcfirst($this->service);
    }

    /**
     * @param string|null $service
     * @return ObjGenDTO
     */
    public function setService(?string $service): ObjGenDTO
    {
        $this->service = $service;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getFormRequest(): ?string
    {
        return $this->formRequest;
    }

    /**
     * @param string|null $formRequest
     * @return ObjGenDTO
     */
    public function setFormRequest(?string $formRequest): ObjGenDTO
    {
        $this->formRequest = $formRequest;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getFormCreateRequest(): ?string
    {
        return $this->formCreateRequest;
    }

    /**
     * @param string|null $formCreateRequest
     * @return ObjGenDTO
     */
    public function setFormCreateRequest(?string $formCreateRequest): ObjGenDTO
    {
        $this->formCreateRequest = $formCreateRequest;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getFormUpdateRequest(): ?string
    {
        return $this->formUpdateRequest;
    }

    /**
     * @param string|null $formUpdateRequest
     * @return ObjGenDTO
     */
    public function setFormUpdateRequest(?string $formUpdateRequest): ObjGenDTO
    {
        $this->formUpdateRequest = $formUpdateRequest;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getFormDeleteRequest(): ?string
    {
        return $this->formDeleteRequest;
    }

    /**
     * @param string|null $formDeleteRequest
     * @return ObjGenDTO
     */
    public function setFormDeleteRequest(?string $formDeleteRequest): ObjGenDTO
    {
        $this->formDeleteRequest = $formDeleteRequest;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getController(): ?string
    {
        return $this->controller;
    }

    /**
     * @param string|null $controller
     * @return ObjGenDTO
     */
    public function setController(?string $controller): ObjGenDTO
    {
        $this->controller = $controller;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getFeatureTest(): ?string
    {
        return $this->featureTest;
    }

    /**
     * @param string|null $featureTest
     * @return ObjGenDTO
     */
    public function setFeatureTest(?string $featureTest): ObjGenDTO
    {
        $this->featureTest = $featureTest;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getTable(): ?string
    {
        return $this->table;
    }

    /**
     * @param string|null $table
     * @return ObjGenDTO
     */
    public function setTable(?string $table): ObjGenDTO
    {
        $this->table = $table;
        return $this;
    }

    /**
     * @return array
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    /**
     * @param array $tags
     * @return ObjGenDTO
     */
    public function setTags(array $tags): ObjGenDTO
    {
        $this->tags = $tags;
        return $this;
    }

    /**
     * @return array
     */
    public function getColumns(): array
    {
        return $this->columns;
    }

    /**
     * @param array $columns
     * @return ObjGenDTO
     */
    public function setColumns(array $columns): ObjGenDTO
    {
        $this->columns = $columns;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getRoutesFileName(): ?string
    {
        return $this->routesFileName;
    }

    /**
     * @param string|null $routesFileName
     * @return ObjGenDTO
     */
    public function setRoutesFileName(?string $routesFileName): ObjGenDTO
    {
        $this->routesFileName = $routesFileName;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getIdType(): ?string
    {
        return $this->idType;
    }

    /**
     * @param string|null $idType
     * @return ObjGenDTO
     */
    public function setIdType(?string $idType): ObjGenDTO
    {
        $this->idType = $idType;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getIdAlias(): ?string
    {
        return $this->idAlias;
    }

    /**
     * @return string|null
     */
    public function getIdVariableMode(): ?string
    {
        if($this->idAlias) return '$' . lcfirst($this->idAlias);

        return '$' . $this->idName;
    }

    /**
     * @return string|null
     */
    public function getIdParameterMode(): ?string
    {
        if($this->idAlias) return lcfirst($this->idAlias);

        return $this->idName;
    }

    /**
     * @return string|null
     */
    public function getIdGetterMode(): ?string
    {
        if($this->idAlias) return 'get' . ucfirst($this->idAlias);

        return 'get' . $this->idName;
    }

    /**
     * @param string|null $idAlias
     * @return ObjGenDTO
     */
    public function setIdAlias(?string $idAlias): ObjGenDTO
    {
        $this->idAlias = $idAlias;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getIdName(): ?string
    {
        return $this->idName;
    }

    /**
     * @param string|null $idName
     * @return ObjGenDTO
     */
    public function setIdName(?string $idName): ObjGenDTO
    {
        $this->idName = $idName;
        return $this;
    }

    /**
     * @return array
     */
    public function getCreatedFiles(): array
    {
        return $this->createdFiles;
    }

    /**
     * @param array $createdFiles
     * @return ObjGenDTO
     */
    public function setCreatedFiles(array $createdFiles): ObjGenDTO
    {
        $this->createdFiles = $createdFiles;
        return $this;
    }

    /**
     * @param string $createdFiles
     * @return $this
     */
    public function addCreatedFile(string $createdFiles): ObjGenDTO
    {
        $this->createdFiles[] = str_replace(PathHelper::basePath() . DIRECTORY_SEPARATOR, "", $createdFiles);
        return $this;
    }

    /**
     * @return array
     */
    public function getModifiedFiles(): array
    {
        return $this->modifiedFiles;
    }

    /**
     * @param array $modifiedFiles
     * @return ObjGenDTO
     */
    public function setModifiedFiles(array $modifiedFiles): ObjGenDTO
    {
        $this->modifiedFiles = $modifiedFiles;
        return $this;
    }

    /**
     * @param string $modifiedFiles
     * @return $this
     */
    public function addModifiedFile(string $modifiedFiles): ObjGenDTO
    {
        $this->modifiedFiles[] = str_replace(PathHelper::basePath() . DIRECTORY_SEPARATOR, "", $modifiedFiles);
        return $this;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @param array $errors
     * @return ObjGenDTO
     */
    public function setErrors(array $errors): ObjGenDTO
    {
        $this->errors = $errors;
        return $this;
    }

    /**
     * @param string $errors
     * @return ObjGenDTO
     */
    public function addError(string $errors): ObjGenDTO
    {
        $this->errors[] = $errors;
        return $this;
    }
}
