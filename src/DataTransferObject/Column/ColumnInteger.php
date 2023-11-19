<?php

namespace JocelimJr\LaravelApiGenerator\DataTransferObject\Column;

class ColumnInteger extends AbstractColumn
{
    public function __construct(object $data = null)
    {
        parent::__construct($data);
        $this->setType('integer');
    }

    private ?string $name = null;
    private bool $autoIncrement = false;
    private bool $unsigned = false;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): ColumnInteger
    {
        $this->name = $name;
        return $this;
    }

    public function autoIncrement(): bool
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
