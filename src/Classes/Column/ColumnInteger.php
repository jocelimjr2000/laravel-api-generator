<?php

namespace JocelimJr\LaravelApiGenerator\Classes\Column;

class ColumnInteger extends AbstractColumn
{
    public function __construct(object $data = null)
    {
        parent::__construct($data);
    }

    private bool $autoIncrement = false;
    private bool $unsigned = false;

    public function isAutoIncrement(): bool
    {
        return $this->autoIncrement;
    }

    public function setAutoIncrement(bool $autoIncrement): ColumnInteger
    {
        $this->autoIncrement = $autoIncrement;
        return $this;
    }

    public function isUnsigned(): bool
    {
        return $this->unsigned;
    }

    public function setUnsigned(bool $unsigned): ColumnInteger
    {
        $this->unsigned = $unsigned;
        return $this;
    }

}
