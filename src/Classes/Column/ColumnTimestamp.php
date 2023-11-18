<?php

namespace JocelimJr\LaravelApiGenerator\Classes\Column;

class ColumnTimestamp extends AbstractColumn
{
    public function __construct(object $data = null)
    {
        parent::__construct($data);
    }

    private int $precision = 0;

    public function getPrecision(): int
    {
        return $this->precision;
    }

    public function setPrecision(int $precision): ColumnTimestamp
    {
        $this->precision = $precision;
        return $this;
    }

}
