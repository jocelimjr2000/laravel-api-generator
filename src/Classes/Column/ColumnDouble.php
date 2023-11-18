<?php

namespace JocelimJr\LaravelApiGenerator\Classes\Column;

class ColumnDouble extends AbstractColumn
{
    public function __construct(object $data = null)
    {
        parent::__construct($data);
    }

    private ?int $total = null;
    private ?int $places = null;
    private bool $unsigned = false;

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
