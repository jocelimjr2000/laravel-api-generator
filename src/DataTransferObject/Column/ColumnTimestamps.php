<?php

namespace JocelimJr\LaravelApiGenerator\DataTransferObject\Column;

class ColumnTimestamps extends AbstractColumn
{
    public function __construct(object $data = null)
    {
        parent::__construct($data);
        $this->setType('timestamps');
        $this->addToJsonIgnore('precision');
    }

    private int $precision = 0;

    public function getPrecision(): int
    {
        return $this->precision;
    }

    public function setPrecision(int $precision): ColumnTimestamps
    {
        $this->precision = $precision;

        if($precision == 0){
            $this->addToJsonIgnore('precision');
        }else{
            $this->rmToJsonIgnore('precision');
        }

        return $this;
    }

}
