<?php

namespace JocelimJr\LaravelApiGenerator\Writers;

use JocelimJr\LaravelApiGenerator\DataTransferObject\ObjGenDTO;
use JocelimJr\LaravelApiGenerator\Helpers\FileHelper;
use JocelimJr\LaravelApiGenerator\Helpers\PathHelper;

class CreateRepositoryWriter extends AbstractWriter
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
        $this->writeInterface();
        $this->writeClass();
        $this->writeProvider();
        $this->addProvider();
    }

    private function writeInterface(): void
    {
        $stub = FileHelper::getStubPath('repository.interface');

        $methods = [];
        $this->addReplaceImports([
            'App\DataTransferObject\\' . $this->objGenDTO->getDto(),
            'App\Models\\' . $this->objGenDTO->getModel()
        ]);

        if($this->objGenDTO->isWriteApiFindAll()) $methods[] = $this->writeRepositoryFindAllSignature();
        if($this->objGenDTO->isWriteApiFindById()) $methods[] = $this->writeRepositoryFindByIdSignature();
        if($this->objGenDTO->isWriteApiCreate()) $methods[] = $this->writeRepositoryCreateSignature();
        if($this->objGenDTO->isWriteApiUpdate()) $methods[] = $this->writeRepositoryUpdateSignature();
        if($this->objGenDTO->isWriteApiDelete()) $methods[] = $this->writeRepositoryDeleteSignature();

        $this->setReplaceMethods($this->loadList($methods, '', ''));

        // Write
        $this->writeFile(['app', 'Interfaces', $this->objGenDTO->getRepositoryInterface()], $this->replaceParameters($stub));
    }

    private function writeClass(): void
    {
        $stub = FileHelper::getStubPath('repository');

        $methods = [];

        if($this->objGenDTO->isDtoLayer())  $this->addReplaceImports('App\DataTransferObject\\' . $this->objGenDTO->getDTO());
        if($this->objGenDTO->isRepositoryLayer())  $this->addReplaceImports('App\Interfaces\\' . $this->objGenDTO->getRepositoryInterface());
        if($this->objGenDTO->isMapperLayer())  $this->addReplaceImports('App\Mappers\\' . $this->objGenDTO->getMapper());

        $this->addReplaceImports('App\Models\\' . $this->objGenDTO->getModel());

        if($this->objGenDTO->isWriteApiFindAll()) $methods[] = $this->writeRepositoryFindAll();
        if($this->objGenDTO->isWriteApiFindById()) $methods[] = $this->writeRepositoryFindById();
        if($this->objGenDTO->isWriteApiCreate()) $methods[] = $this->writeRepositoryCreate();
        if($this->objGenDTO->isWriteApiUpdate()) $methods[] = $this->writeRepositoryUpdate();
        if($this->objGenDTO->isWriteApiDelete()) $methods[] = $this->writeRepositoryDelete();

        $this->setReplaceMethods($this->loadList($methods, '', ''));

        // Write
        $this->writeFile(['app', 'Repositories', $this->objGenDTO->getRepository()], $this->replaceParameters($stub));
    }

    private function writeProvider(): void
    {
        @mkdir(PathHelper::basePath(['app', 'Providers']), 0777, true);

        $file = PathHelper::basePath(['app', 'Providers', 'RepositoryServiceProvider.php']);

        // Update file
        if(file_exists($file)){

            // Include binds
            $lines = file($file);
            $content = '';
            $inMethod = false;
            $writeUseInterface = true;
            $writeUseRepository = true;
            $writeBind = true;

            foreach($lines as $line) {
                if(str_contains($line,  $this->prepareUseInterface())){
                    $writeUseInterface = false;
                    continue;
                }

                if(str_contains($line,  $this->prepareUseRepository())){
                    $writeUseRepository = false;
                    continue;
                }

                if(str_contains($line,  $this->prepareBind())){
                    $writeBind = false;
                }
            }

            foreach($lines as $k => $line) {

                if(str_contains($line, 'register')){
                    $inMethod = true;
                }

                if($k > 0 && str_contains($lines[$k -1], 'use ') && !str_contains($line, 'use ')){
                    if($writeUseInterface){
                        $content .= $this->prepareUseInterface() . PHP_EOL;
                    }

                    if($writeUseRepository){
                        $content .= $this->prepareUseRepository() . PHP_EOL;
                    }
                }

                if($inMethod && str_contains($line, '}')){
                    $inMethod = false;

                    if($writeBind) {
                        $content .= '        ' . $this->prepareBind() . PHP_EOL;
                    }
                }

                $content .= $line;
            }

            $this->objGenDTO->addModifiedFile($file);

            $saved_file = file_put_contents($file, $content);

            if (!($saved_file === false || $saved_file == -1)) {
                $this->objGenDTO->addModifiedFile($file);
            }else{
                $this->objGenDTO->addError('Error editing file: ' . $file);
            }

            return;
        }

        // Create file

        $content = FileHelper::getStubPath('repository.provider');
        $content = str_replace('{{ uses }}', $this->prepareUseInterface() . PHP_EOL . $this->prepareUseRepository() . PHP_EOL, $content);
        $content = str_replace('{{ bind }}', $this->prepareBind(), $content);

        $this->objGenDTO->addCreatedFile($file);

        $saved_file = file_put_contents($file, $content);

        if (!($saved_file === false || $saved_file == -1)) {
            $this->objGenDTO->addCreatedFile($file);
        }else{
            $this->objGenDTO->addError('Error creating file: ' . $file);
        }
    }

    private function addProvider(): void
    {
        $file = PathHelper::basePath(['config', 'app.php']);

        if(!file_exists($file)){
            $this->objGenDTO->addError('File not found: ' . $file);
            return;
        }

        $lines = file($file);
        $providersArr = false;
        $content = '';

        foreach($lines as $line) {
            if(str_contains($line, "'providers' => [")){
                $providersArr = true;
            }

            if($providersArr && str_contains($line, 'App\Providers\RepositoryServiceProvider::class')){
                return;
            }

            if($providersArr && str_contains($line, "]")){
                $content .= '        App\Providers\RepositoryServiceProvider::class,' . PHP_EOL;
                $providersArr = false;
            }

            $content .= $line;
        }

        $saved_file = file_put_contents($file, $content);

        if (!($saved_file === false || $saved_file == -1)) {
            $this->objGenDTO->addModifiedFile($file);
        }else{
            $this->objGenDTO->addError('Error editing file: ' . $file);
        }
    }

    private function prepareBind(): string
    {
        return '$this->app->bind(' . $this->objGenDTO->getRepositoryInterface() . '::class, ' . $this->objGenDTO->getRepository() . '::class);';
    }

    private function prepareUseInterface(): string
    {
        return 'use App\Interfaces\\' . $this->objGenDTO->getRepositoryInterface() . ';';
    }

    private function prepareUseRepository(): string
    {
        return 'use App\Repositories\\' . $this->objGenDTO->getRepository() . ';';
    }

    private function writeRepositoryFindAllSignature(): string
    {
        return FileHelper::getStubPath('content.repository.method.findAll.signature');
    }

    private function writeRepositoryFindAll(): string
    {
        return FileHelper::getStubPath('content.repository.method.findAll.method');
    }

    private function writeRepositoryFindByIdSignature(): string
    {
        return FileHelper::getStubPath('content.repository.method.findById.signature');
    }

    private function writeRepositoryFindById(): string
    {
        return FileHelper::getStubPath('content.repository.method.findById.method');
    }

    private function writeRepositoryCreateSignature(): string
    {
        return FileHelper::getStubPath('content.repository.method.create.signature');
    }

    private function writeRepositoryCreate(): string
    {
        return FileHelper::getStubPath('content.repository.method.create.method');
    }

    private function writeRepositoryUpdateSignature(): string
    {
        return FileHelper::getStubPath('content.repository.method.update.signature');
    }

    private function writeRepositoryUpdate(): string
    {
        return FileHelper::getStubPath('content.repository.method.update.method');
    }

    private function writeRepositoryDeleteSignature(): string
    {
        return FileHelper::getStubPath('content.repository.method.delete.signature');
    }

    private function writeRepositoryDelete(): string
    {
        return FileHelper::getStubPath('content.repository.method.delete.method');
    }
}
