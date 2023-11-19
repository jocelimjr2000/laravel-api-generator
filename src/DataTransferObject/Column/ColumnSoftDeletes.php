<?php

namespace JocelimJr\LaravelApiGenerator\DataTransferObject\Column;

class ColumnSoftDeletes extends AbstractColumn
{
    public function __construct(object $data = null)
    {
        parent::__construct($data);
        $this->setType('softDeletes');
    }

    private ?string $name = 'deleted_at';
    private int $precision = 0;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): ColumnSoftDeletes
    {
        $this->name = $name;
        return $this;
    }

    public function getPrecision(): int
    {
        return $this->precision;
    }

    public function setPrecision(int $precision): ColumnSoftDeletes
    {
        $this->precision = $precision;
        return $this;
    }

}
