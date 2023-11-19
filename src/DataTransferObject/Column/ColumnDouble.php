<?php

namespace JocelimJr\LaravelApiGenerator\DataTransferObject\Column;

class ColumnDouble extends AbstractColumn
{
    public function __construct(object $data = null)
    {
        parent::__construct($data);
        $this->setType('double');
    }

    private ?string $name = null;
    private ?int $total = null;
    private ?int $places = null;
    private bool $unsigned = false;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): ColumnDouble
    {
        $this->name = $name;
        return $this;
    }

    public function getTotal(): ?int
    {
        return $this->total;
    }

    public function setTotal(?int $total): ColumnDouble
    {
        $this->total = $total;
        return $this;
    }

    public function getPlaces(): ?int
    {
        return $this->places;
    }

    public function setPlaces(?int $places): ColumnDouble
    {
        $this->places = $places;
        return $this;
    }

    public function isUnsigned(): bool
    {
        return $this->unsigned;
    }

    public function setUnsigned(bool $unsigned): ColumnDouble
    {
        $this->unsigned = $unsigned;
        return $this;
    }

}
