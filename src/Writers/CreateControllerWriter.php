<?php

namespace JocelimJr\LaravelApiGenerator\Writers;

use JocelimJr\LaravelApiGenerator\DataTransferObject\ObjGenDTO;
use JocelimJr\LaravelApiGenerator\Helpers\FileHelper;

class CreateControllerWriter extends AbstractWriter
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
        $stub = FileHelper::getStubPath('controller');

        $this->addReplaceImports([
            'App\Http\Requests\\' . $this->objGenDTO->getFormCreateRequest(),
            'App\Http\Requests\\' . $this->objGenDTO->getFormDeleteRequest(),
            'App\Http\Requests\\' . $this->objGenDTO->getFormUpdateRequest(),
        ]);

        $methods = [];
        $parameters = [];
        $breakLastLine = false;

        if ($this->objGenDTO->isWriteApiFindAll()) $methods[] = $this->writeApiFindAll();
        if ($this->objGenDTO->isWriteApiFindById()) $methods[] = $this->writeApiFindById();
        if ($this->objGenDTO->isWriteApiCreate()) $methods[] = $this->writeApiCreate();
        if ($this->objGenDTO->isWriteApiUpdate()) $methods[] = $this->writeApiUpdate();
        if ($this->objGenDTO->isWriteApiDelete()) $methods[] = $this->writeApiDelete();

        if($this->objGenDTO->isServiceLayer() || $this->objGenDTO->isRepositoryLayer()) {
            $breakLastLine = true;

            if($this->objGenDTO->isServiceLayer()){
                $parameters[] = 'private ' . $this->objGenDTO->getService() . ' ' . $this->objGenDTO->getServiceVariableMode();
                $this->addReplaceImports('App\Services\\' . $this->objGenDTO->getService());
            }else if($this->objGenDTO->isRepositoryLayer()){
                $parameters[] = 'private ' . $this->objGenDTO->getRepositoryInterface() . ' ' . $this->objGenDTO->getRepositoryVariableMode();
                $this->addReplaceImports('App\Interfaces\\' . $this->objGenDTO->getRepositoryInterface());
            }

        }else{
            $this->addReplaceImports('App\Models\\' . $this->objGenDTO->getModel());
        }

        if($this->objGenDTO->isMapperLayer()) {
            $this->addReplaceImports('App\Mappers\\' . $this->objGenDTO->getMapper());
        }else{
            if($this->objGenDTO->isDtoLayer()) {
                $methods[] = FileHelper::getStubPath('content.controller.method.modelToDTO');
            }
        }

        if($this->objGenDTO->isDtoLayer()) {
            $this->addReplaceImports('App\DataTransferObject\\' . $this->objGenDTO->getDto());
        }

        $this->addReplaceImports([
            'Illuminate\Http\JsonResponse',
            'Illuminate\Http\Request'
        ]);

        $this->setReplaceConstruct($this->prepareConstructor());
        $this->setReplaceParameters($this->loadList(list: $parameters, prefix: '    ', breakLastLine: $breakLastLine));
        $this->setReplaceMethods($this->loadList($methods, '', ''));

        // Write
        $this->writeFile(['app', 'Http', 'Controllers', $this->objGenDTO->getController()], $this->replaceParameters($stub));
    }

    private function prepareConstructor(): string
    {
        if($this->objGenDTO->isServiceLayer()){
            return FileHelper::getStubPath('content.construct.service');
        }

        if($this->objGenDTO->isRepositoryLayer()){
            return FileHelper::getStubPath('content.construct.repository');
        }

        return '';
    }

    private function writeApiFindAll(): string
    {
        if($this->objGenDTO->isServiceLayer() || $this->objGenDTO->isRepositoryLayer()) {
            return FileHelper::getStubPath('content.controller.method.findAll.A');
        }

        if($this->objGenDTO->isMapperLayer()) {
            return FileHelper::getStubPath('content.controller.method.findAll.B');
        }

        if($this->objGenDTO->isDtoLayer()) {
            return FileHelper::getStubPath('content.controller.method.findAll.C');
        }

        return FileHelper::getStubPath('content.controller.method.findAll.D');
    }

    private function writeApiFindById(): string
    {
        if($this->objGenDTO->isServiceLayer() || $this->objGenDTO->isRepositoryLayer()) {
            return FileHelper::getStubPath('content.controller.method.findById.A');
        }

        if($this->objGenDTO->isMapperLayer()) {
            return FileHelper::getStubPath('content.controller.method.findById.B');
        }

        if($this->objGenDTO->isDtoLayer()) {
            return FileHelper::getStubPath('content.controller.method.findById.C');
        }

        return FileHelper::getStubPath('content.controller.method.findById.D');
    }

    private function writeApiCreate(): string
    {
        if($this->objGenDTO->isServiceLayer() || $this->objGenDTO->isRepositoryLayer()) {
            return FileHelper::getStubPath('content.controller.method.create.A');
        }

        if($this->objGenDTO->isMapperLayer()) {
            return FileHelper::getStubPath('content.controller.method.create.B');
        }

        if($this->objGenDTO->isDtoLayer()) {
            return FileHelper::getStubPath('content.controller.method.create.C');
        }

        return FileHelper::getStubPath('content.controller.method.create.D');
    }

    private function writeApiUpdate(): string
    {
        if($this->objGenDTO->isServiceLayer() || $this->objGenDTO->isRepositoryLayer()) {
            return FileHelper::getStubPath('content.controller.method.update.A');
        }

        if($this->objGenDTO->isMapperLayer()) {
            return FileHelper::getStubPath('content.controller.method.update.B');
        }

        if($this->objGenDTO->isDtoLayer()) {
            return FileHelper::getStubPath('content.controller.method.update.C');
        }

        return FileHelper::getStubPath('content.controller.method.update.D');
    }

    private function writeApiDelete(): string
    {
        if($this->objGenDTO->isServiceLayer() || $this->objGenDTO->isRepositoryLayer()) {
            return FileHelper::getStubPath('content.controller.method.delete.A');
        }

        return FileHelper::getStubPath('content.controller.method.delete.B');
    }

}
