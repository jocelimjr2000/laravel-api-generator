<?php

namespace JocelimJr\LaravelApiGenerator\DataTransferObject;

class JsonDefinitionsDTO extends AbstractDTO
{
    private ?string $apiName = null;
    private ?string $module = null;
    private array $tags = [];
    private ?string $routePrefix = null;
    private ?string $prefixDateMigration = null;
    private ?string $table = null;
    private array $columns = [];
    private PrimaryKeyDTO $primaryKey;
    private ArchitectureDTO $architecture;
    private WriteApiDTO $writeApi;
    private CreateFileDTO $createFile;
    private FileNameDTO $fileName;
    private SwaggerDTO $swagger;

    public function __construct()
    {
        $config = config('laravel-generator.default');

        $classes = [
            'primaryKey' => PrimaryKeyDTO::class,
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

    public function getTags(): array
    {
        return $this->tags;
    }

    public function setTags(array $tags): JsonDefinitionsDTO
    {
        $this->tags = $tags;
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

    public function getPrefixDateMigration(): ?string
    {
        return $this->prefixDateMigration;
    }

    public function setPrefixDateMigration(?string $prefixDateMigration): JsonDefinitionsDTO
    {
        $this->prefixDateMigration = $prefixDateMigration;
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

    public function getColumns(): array
    {
        return $this->columns;
    }

    public function setColumns(array $columns): JsonDefinitionsDTO
    {
        $this->columns = $columns;
        return $this;
    }

    public function getPrimaryKey(): PrimaryKeyDTO
    {
        return $this->primaryKey;
    }

    public function setPrimaryKey(PrimaryKeyDTO $primaryKey): JsonDefinitionsDTO
    {
        $this->primaryKey = $primaryKey;
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

}
