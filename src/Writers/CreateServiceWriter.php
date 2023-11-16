<?php

namespace JocelimJr\LaravelApiGenerator\Writers;

use JocelimJr\LaravelApiGenerator\DataTransferObject\ObjGenDTO;
use JocelimJr\LaravelApiGenerator\Helpers\FileHelper;

class CreateServiceWriter extends AbstractWriter
{

    /**
     * @param ObjGenDTO $objGenDTO
     */
    public function __construct(ObjGenDTO $objGenDTO)
    {
        parent::__construct($objGenDTO);
    }

    public function write()
    {
        $stub = FileHelper::getStubPath('service');

        $methods = [];
        $parameters = '';

        if($this->objGenDTO->isWriteApiFindAll()) $methods[] = $this->writeFindAll();
        if($this->objGenDTO->isWriteApiFindById()) $methods[] = $this->writeFindById();
        if($this->objGenDTO->isWriteApiCreate()) $methods[] = $this->writeCreate();
        if($this->objGenDTO->isWriteApiUpdate()) $methods[] = $this->writeUpdate();
        if($this->objGenDTO->isWriteApiDelete()) $methods[] = $this->writeDelete();

        if(!$this->objGenDTO->isMapperLayer() && $this->objGenDTO->isDtoLayer()){
            $methods[] = FileHelper::getStubPath('content.controller.method.modelToDTO');
        }

        if($this->objGenDTO->isDtoLayer()) {
            $this->addReplaceImports('App\DataTransferObject\\' . $this->objGenDTO->getDto());
        }

        if($this->objGenDTO->isRepositoryLayer()){
            $parameters .= '    private ' . $this->objGenDTO->getRepositoryInterface() . ' $' . lcfirst($this->objGenDTO->getRepository()) . ';' . PHP_EOL;
            $this->addReplaceImports('App\Interfaces\\' . $this->objGenDTO->getRepositoryInterface());
        }

        if(!$this->objGenDTO->isRepositoryLayer()){
            if($this->objGenDTO->isMapperLayer()) {
                $this->addReplaceImports('App\Mappers\\' . $this->objGenDTO->getMapper());
            }

            $this->addReplaceImports('App\Models\\' . $this->objGenDTO->getModel());
        }

        $this->setReplaceConstruct($this->prepareConstructor());
        $this->setReplaceParameters($parameters);
        $this->setReplaceMethods($this->loadList($methods, '', ''));

        // Write
        $this->writeFile(['app', 'Services', $this->objGenDTO->getService()], $this->replaceParameters($stub));
    }

    private function prepareConstructor(): string
    {
        if($this->objGenDTO->isRepositoryLayer()){
            return FileHelper::getStubPath('content.construct.repository');
        }

        return '';
    }

    private function writeFindAll(): string
    {
        if($this->objGenDTO->isRepositoryLayer()){
            return FileHelper::getStubPath('content.service.findAll.method.A');
        }

        if($this->objGenDTO->isMapperLayer()){
            return FileHelper::getStubPath('content.service.findAll.method.B');
        }

        if($this->objGenDTO->isDtoLayer()){
            return FileHelper::getStubPath('content.service.findAll.method.C');
        }

        return FileHelper::getStubPath('content.service.findAll.method.D');
    }

    private function writeFindById(): string
    {
        if($this->objGenDTO->isRepositoryLayer()){
            return FileHelper::getStubPath('content.service.findById.method.A');
        }

        if($this->objGenDTO->isMapperLayer()){
            return FileHelper::getStubPath('content.service.findById.method.B');
        }

        if($this->objGenDTO->isDtoLayer()){
            return FileHelper::getStubPath('content.service.findById.method.C');
        }

        return FileHelper::getStubPath('content.service.findById.method.D');
    }

    private function writeCreate(): string
    {
        if($this->objGenDTO->isRepositoryLayer()){
            return FileHelper::getStubPath('content.service.create.method.A');
        }

        if($this->objGenDTO->isMapperLayer()){
            return FileHelper::getStubPath('content.service.create.method.B');
        }

        if($this->objGenDTO->isDtoLayer()){
            return FileHelper::getStubPath('content.service.create.method.C');
        }

        return FileHelper::getStubPath('content.service.create.method.D');
    }

    private function writeUpdate(): string
    {
        if($this->objGenDTO->isRepositoryLayer()){
            return FileHelper::getStubPath('content.service.update.method.A');
        }

        if($this->objGenDTO->isMapperLayer()){
            return FileHelper::getStubPath('content.service.update.method.B');
        }

        if($this->objGenDTO->isDtoLayer()){
            return FileHelper::getStubPath('content.service.update.method.C');
        }

        return FileHelper::getStubPath('content.service.update.method.D');
    }

    private function writeDelete(): string
    {
        if($this->objGenDTO->isRepositoryLayer()){
            return FileHelper::getStubPath('content.service.method.delete.A');
        }

        return FileHelper::getStubPath('content.service.method.delete.B');
    }
}
