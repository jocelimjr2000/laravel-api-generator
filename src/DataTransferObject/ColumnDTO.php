<?php

namespace JocelimJr\LaravelApiGenerator\DataTransferObject;

use Doctrine\Common\Annotations\AnnotationReader;
use ReflectionClass;

class ColumnDTO extends AbstractDTO
{
    private ?string $name = null;
    private ?string $type = null;
    private ?string $alias = null;
    private ?string $total = null;
    private ?string $places = null;
    private ?int $length = null;
    private int $precision = 0;
    private string|bool|null $default = null;
    private bool $nullable = false;
    private bool $primary = false;
    private bool $unique = false;
    private bool $autoIncrement = false;
    private bool $deletedAt = false;
    private bool $createdAt = false;
    private bool $updatedAt = false;
    private bool $unsigned = false;

    public function __construct(object $data = null)
    {
        if($data){
            $reflectionClass = new ReflectionClass($this);

            foreach($reflectionClass->getProperties() as $p){
                $n = $p->getName();

                if(!isset($data->$n)){
                    continue;
                }

                $setter = 'set' . ucfirst($p->getName());

                $this->$setter($data->$n);
            }
        }
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): ColumnDTO
    {
        $this->name = $name;
        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): ColumnDTO
    {
        $this->type = $type;
        return $this;
    }

    public function getAlias(): ?string
    {
        return $this->alias;
    }

    public function setAlias(?string $alias): ColumnDTO
    {
        $this->alias = $alias;
        return $this;
    }

    public function getTotal(): ?string
    {
        return $this->total;
    }

    public function setTotal(?string $total): ColumnDTO
    {
        $this->total = $total;
        return $this;
    }

    public function getPlaces(): ?string
    {
        return $this->places;
    }

    public function setPlaces(?string $places): ColumnDTO
    {
        $this->places = $places;
        return $this;
    }

    public function getLength(): ?int
    {
        return $this->length;
    }

    public function setLength(?int $length): ColumnDTO
    {
        $this->length = $length;
        return $this;
    }

    public function getPrecision(): int
    {
        return $this->precision;
    }

    public function setPrecision(int $precision): ColumnDTO
    {
        $this->precision = $precision;
        return $this;
    }

    public function getDefault(): bool|string|null
    {
        return $this->default;
    }

    public function setDefault(bool|string|null $default): ColumnDTO
    {
        $this->default = $default;
        return $this;
    }

    public function isNullable(): bool
    {
        return $this->nullable;
    }

    public function setNullable(bool $nullable): ColumnDTO
    {
        $this->nullable = $nullable;
        return $this;
    }

    public function isPrimary(): bool
    {
        return $this->primary;
    }

    public function setPrimary(bool $primary): ColumnDTO
    {
        $this->primary = $primary;
        return $this;
    }

    public function isUnique(): bool
    {
        return $this->unique;
    }

    public function setUnique(bool $unique): ColumnDTO
    {
        $this->unique = $unique;
        return $this;
    }

    public function isAutoIncrement(): bool
    {
        return $this->autoIncrement;
    }

    public function setAutoIncrement(bool $autoIncrement): ColumnDTO
    {
        $this->autoIncrement = $autoIncrement;
        return $this;
    }

    public function isDeletedAt(): bool
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(bool $deletedAt): ColumnDTO
    {
        $this->deletedAt = $deletedAt;
        return $this;
    }

    public function isCreatedAt(): bool
    {
        return $this->createdAt;
    }

    public function setCreatedAt(bool $createdAt): ColumnDTO
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function isUpdatedAt(): bool
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(bool $updatedAt): ColumnDTO
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function isUnsigned(): bool
    {
        return $this->unsigned;
    }

    public function setUnsigned(bool $unsigned): ColumnDTO
    {
        $this->unsigned = $unsigned;
        return $this;
    }

}
