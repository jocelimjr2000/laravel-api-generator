<?php

namespace JocelimJr\LaravelApiGenerator\DataTransferObject\Column;

use JocelimJr\LaravelApiGenerator\DataTransferObject\AbstractDTO;

class AbstractColumn extends AbstractDTO
{
    private ?string $type = null;

    // Extra
    private bool $primary = false;
    private bool $autoIncrement = false;
    private bool $nullable = false;
    private bool $unique = false;
    private bool $fillable = false;
    private mixed $default = null;

    public function __construct()
    {
        // Set Type
        $array = explode('\\', get_called_class());
        $className = end($array);
        $this->type = lcfirst(str_replace('Column', '', $className));

        $this->addToJsonIgnore([
            'primary',
            'autoIncrement',
            'nullable',
            'unique',
            'default'
        ]);
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): AbstractColumn
    {
        $this->type = $type;
        return $this;
    }

    public function isPrimary(): bool
    {
        return $this->primary;
    }

    public function setPrimary(bool $primary): AbstractColumn
    {
        $this->primary = $primary;

        if($primary){
            $this->rmToJsonIgnore('primary');
        }else{
            $this->addToJsonIgnore('primary');
        }

        return $this;
    }

    public function isAutoIncrement(): bool
    {
        return $this->autoIncrement;
    }

    public function setAutoIncrement(bool $autoIncrement): AbstractColumn
    {
        $this->autoIncrement = $autoIncrement;

        if($autoIncrement){
            $this->rmToJsonIgnore('autoIncrement');
        }else{
            $this->addToJsonIgnore('autoIncrement');
        }

        return $this;
    }

    public function isNullable(): bool
    {
        return $this->nullable;
    }

    public function setNullable(bool $nullable): AbstractColumn
    {
        $this->nullable = $nullable;

        if($nullable){
            $this->rmToJsonIgnore('nullable');
        }else{
            $this->addToJsonIgnore('nullable');
        }

        return $this;
    }

    public function isUnique(): bool
    {
        return $this->unique;
    }

    public function setUnique(bool $unique): AbstractColumn
    {
        $this->unique = $unique;

        if($unique){
            $this->rmToJsonIgnore('unique');
        }else{
            $this->addToJsonIgnore('unique');
        }

        return $this;
    }

    public function isFillable(): bool
    {
        return $this->fillable;
    }

    public function setFillable(bool $fillable): AbstractColumn
    {
        $this->fillable = $fillable;
        return $this;
    }

    public function getDefault(): mixed
    {
        return $this->default;
    }

    public function setDefault(mixed $default): AbstractColumn
    {
        $this->default = $default;

        if($default){
            $this->rmToJsonIgnore('default');
        }else{
            $this->addToJsonIgnore('default');
        }

        return $this;
    }

}
