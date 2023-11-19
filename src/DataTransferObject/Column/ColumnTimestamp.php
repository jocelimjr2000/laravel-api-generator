<?php

namespace JocelimJr\LaravelApiGenerator\DataTransferObject\Column;

class ColumnTimestamp extends AbstractColumn
{
    public function __construct()
    {
        parent::__construct();
        $this->setType('timestamp');
        $this->addToJsonIgnore([
            'precision',
            'createdAt',
            'updatedAt',
            'deletedAt'
        ]);
    }

    private ?string $name = null;
    private int $precision = 0;
    private bool $createdAt = false;
    private bool $updatedAt = false;
    private bool $deletedAt = false;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): ColumnTimestamp
    {
        $this->name = $name;
        return $this;
    }

    public function getPrecision(): int
    {
        return $this->precision;
    }

    public function setPrecision(int $precision): ColumnTimestamp
    {
        $this->precision = $precision;

        if($precision == 0){
            $this->addToJsonIgnore('precision');
        }else{
            $this->rmToJsonIgnore('precision');
        }

        return $this;
    }

    public function isCreatedAt(): bool
    {
        return $this->createdAt;
    }

    public function setCreatedAt(bool $createdAt): ColumnTimestamp
    {
        $this->createdAt = $createdAt;

        if($createdAt){
            $this->rmToJsonIgnore('createdAt');
        }else{
            $this->addToJsonIgnore('createdAt');
        }

        return $this;
    }

    public function isUpdatedAt(): bool
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(bool $updatedAt): ColumnTimestamp
    {
        $this->updatedAt = $updatedAt;

        if($updatedAt){
            $this->rmToJsonIgnore('updatedAt');
        }else{
            $this->addToJsonIgnore('updatedAt');
        }

        return $this;
    }

    public function isDeletedAt(): bool
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(bool $deletedAt): ColumnTimestamp
    {
        $this->deletedAt = $deletedAt;

        if($deletedAt){
            $this->rmToJsonIgnore('deletedAt');
        }else{
            $this->addToJsonIgnore('deletedAt');
        }

        return $this;
    }

}
