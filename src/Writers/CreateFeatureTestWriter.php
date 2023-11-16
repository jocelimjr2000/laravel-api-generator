<?php

namespace JocelimJr\LaravelApiGenerator\Writers;

use JocelimJr\LaravelApiGenerator\DataTransferObject\ObjGenDTO;
use JocelimJr\LaravelApiGenerator\Helpers\FileHelper;
use JocelimJr\LaravelApiGenerator\Helpers\ParametersHelper;
use Exception;

class CreateFeatureTestWriter extends AbstractWriter
{

    /**
     * @param ObjGenDTO $objGenDTO
     */
    public function __construct(ObjGenDTO $objGenDTO)
    {
        parent::__construct($objGenDTO);
    }

    /**
     * @throws Exception
     */
    public function write()
    {
        $stub = FileHelper::getStubPath('test.feature');

        $methods = [];

        if($this->objGenDTO->isWriteApiCreate()) $methods[] = $this->writeTestCreate();
        if($this->objGenDTO->isWriteApiUpdate()) $methods[] = $this->writeTestUpdate();
        if($this->objGenDTO->isWriteApiFindById()) $methods[] = $this->writeTestFindById();
        if($this->objGenDTO->isWriteApiDelete()) $methods[] = $this->writeTestDelete();
        if($this->objGenDTO->isWriteApiFindAll()) $methods[] = $this->writeTestFindAll();

        $dataCreate = '';
        $dataUpdate = '';

        foreach($this->objGenDTO->getColumns() as $column){

            if(
                (isset($column->createdAt) && $column->createdAt === true) ||
                (isset($column->updatedAt) && $column->updatedAt === true) ||
                (isset($column->deletedAt) && $column->deletedAt === true) ||
                (isset($column->primary) && $column->primary === true) ||
                $column->type == 'id'
            ){
                continue;
            }

            $_c = ($column->alias ?? $column->name);

            $dataCreate .= PHP_EOL . "            '" . $_c . "' => " . (ParametersHelper::generateDataByType($column->type, "'", $column->example ?? null)) . ",";
            $dataUpdate .= PHP_EOL . "            '" . $_c . "' => " . (ParametersHelper::generateDataByType($column->type, "'", $column->example ?? null)) . ",";
        }

        $this->addExtraReplaceParametersToReplace([
            '{{ methods }}' => $this->loadList($methods, '', ''),
            '{{ dataCreate }}' => $dataCreate,
            '{{ dataUpdate }}' => $dataUpdate,
        ]);

        // Write
        $this->writeFile(['tests', 'Feature', $this->objGenDTO->getFeatureTest()], $this->replaceParameters($stub));
    }

    private function writeTestFindAll(): string
    {
        return FileHelper::getStubPath('content.featureTest.method.findAll');
    }

    private function writeTestFindById(): string
    {
        return FileHelper::getStubPath('content.featureTest.method.findById');
    }

    /**
     * @return string
     */
    private function writeTestCreate(): string
    {
        return FileHelper::getStubPath('content.featureTest.method.create');
    }

    /**
     * @return string
     */
    private function writeTestUpdate(): string
    {
        return FileHelper::getStubPath('content.featureTest.method.update');
    }

    /**
     * @return string
     */
    private function writeTestDelete(): string
    {
        return FileHelper::getStubPath('content.featureTest.method.delete');
    }
}
