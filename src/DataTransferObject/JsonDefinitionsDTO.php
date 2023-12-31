<?php
/** @noinspection PhpUnused */

namespace JocelimJr\LaravelApiGenerator\DataTransferObject;

use JocelimJr\LaravelApiGenerator\DataTransferObject\Column\AbstractColumn;

class JsonDefinitionsDTO extends AbstractDTO
{
    private ?string $apiName = null;
    private ?string $module = null;
    private ?string $routePrefix = null;
    private ?string $table = null;
    private ?ArchitectureDTO $architecture = null;
    private ?WriteApiDTO $writeApi = null;
    private ?CreateFileDTO $createFile = null;
    private ?FileNameDTO $fileName = null;
    private ?SwaggerDTO $swagger = null;
    private array $columns = [];

    public function __construct()
    {
        $this->loadDataByConfig();
    }

    public function getApiName(): ?string
    {
        return $this->apiName;
    }

    public function setApiName(?string $apiName): JsonDefinitionsDTO
    {
        $this->apiName = $apiName;
        return $this;
    }

    public function getModule(): ?string
    {
        return $this->module;
    }

    public function setModule(?string $module): JsonDefinitionsDTO
    {
        $this->module = $module;
        return $this;
    }

    public function getRoutePrefix(): ?string
    {
        return $this->routePrefix;
    }

    public function setRoutePrefix(?string $routePrefix): JsonDefinitionsDTO
    {
        $this->routePrefix = $routePrefix;
        return $this;
    }

    public function getTable(): ?string
    {
        return $this->table;
    }

    public function setTable(?string $table): JsonDefinitionsDTO
    {
        $this->table = $table;
        return $this;
    }

    public function getArchitecture(): ArchitectureDTO
    {
        return $this->architecture;
    }

    public function setArchitecture(ArchitectureDTO $architecture): JsonDefinitionsDTO
    {
        $this->architecture = $architecture;
        return $this;
    }

    public function getWriteApi(): WriteApiDTO
    {
        return $this->writeApi;
    }

    public function setWriteApi(WriteApiDTO $writeApi): JsonDefinitionsDTO
    {
        $this->writeApi = $writeApi;
        return $this;
    }

    public function getCreateFile(): CreateFileDTO
    {
        return $this->createFile;
    }

    public function setCreateFile(CreateFileDTO $createFile): JsonDefinitionsDTO
    {
        $this->createFile = $createFile;
        return $this;
    }

    public function getFileName(): FileNameDTO
    {
        return $this->fileName;
    }

    public function setFileName(FileNameDTO $fileName): JsonDefinitionsDTO
    {
        $this->fileName = $fileName;
        return $this;
    }

    public function getSwagger(): SwaggerDTO
    {
        return $this->swagger;
    }

    public function setSwagger(SwaggerDTO $swagger): JsonDefinitionsDTO
    {
        $this->swagger = $swagger;
        return $this;
    }

    public function getColumns(): array
    {
        return $this->columns;
    }

    public function setColumns(array $columns): JsonDefinitionsDTO
    {
        $this->columns = $columns;
        return $this;
    }

    public function addColumn(AbstractColumn $column): JsonDefinitionsDTO
    {
        $this->columns[] = $column;
        return $this;
    }

    private function loadDataByConfig(): void
    {
        $config = config('laravel-generator.default');

        $classes = [
            'architecture' => ArchitectureDTO::class,
            'writeApi' => WriteApiDTO::class,
            'createFile' => CreateFileDTO::class,
            'fileName' => FileNameDTO::class,
            'swagger' => SwaggerDTO::class
        ];

        foreach($config as $p => $v){
            if(in_array($p, array_keys($classes))){
                $dto = $classes[$p];

                $this->{$p} = new $dto();
            }else{
                $this->{$p} = $v;
            }
        }
    }

    public function loadDataByName(): JsonDefinitionsDTO
    {
        if($this->apiName){
            $lowerApiName = strtolower($this->apiName);
            $ucFirstApiName = ucfirst($this->apiName);

            if(!$this->routePrefix) $this->routePrefix = '/' .$lowerApiName;
            if(!$this->table) $this->table = $lowerApiName;

            if(!$this->fileName->getRoutes()) $this->fileName->setRoutes("api-" . $lowerApiName);
            if(!$this->fileName->getController()) $this->fileName->setController($ucFirstApiName . 'Controller');
            if(!$this->fileName->getFormRequest()) $this->fileName->setFormRequest($ucFirstApiName . 'Request');
            if(!$this->fileName->getFormCreateRequest()) $this->fileName->setFormCreateRequest($ucFirstApiName . 'CreateRequest');
            if(!$this->fileName->getFormUpdateRequest()) $this->fileName->setFormUpdateRequest($ucFirstApiName . 'UpdateRequest');
            if(!$this->fileName->getFormDeleteRequest()) $this->fileName->setFormDeleteRequest($ucFirstApiName . 'DeleteRequest');
            if(!$this->fileName->getModel()) $this->fileName->setModel($ucFirstApiName);
            if(!$this->fileName->getRepository()) $this->fileName->setRepository($ucFirstApiName . 'Repository');
            if(!$this->fileName->getRepositoryInterface()) $this->fileName->setRepositoryInterface($ucFirstApiName . 'RepositoryInterface');
            if(!$this->fileName->getMapper()) $this->fileName->setMapper($ucFirstApiName . 'Mapper');
            if(!$this->fileName->getDto()) $this->fileName->setDto($ucFirstApiName . 'DTO');
            if(!$this->fileName->getService()) $this->fileName->setService($ucFirstApiName . 'Service');
            if(!$this->fileName->getFeatureTest()) $this->fileName->setFeatureTest($ucFirstApiName . 'Test');
            if(!$this->fileName->getMigration()) $this->fileName->setMigration(date('Y_m_d_His') . '_create_' . $lowerApiName . '_table');
            if(count($this->swagger->getTags()) == 0) $this->swagger->setTags([$ucFirstApiName]);
        }

        return $this;
    }
}
