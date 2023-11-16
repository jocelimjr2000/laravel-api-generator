<?php /** @noinspection SpellCheckingInspection */

namespace JocelimJr\LaravelApiGenerator\Writers;

use JocelimJr\LaravelApiGenerator\DataTransferObject\ObjGenDTO;
use JocelimJr\LaravelApiGenerator\Helpers\FileHelper;

class CreateMapperWriter extends AbstractWriter
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
        $stub = FileHelper::getStubPath('mapper');

        $this->addReplaceImports([
            'App\DataTransferObject\\' . $this->objGenDTO->getDto(),
            'App\Models\\' . $this->objGenDTO->getModel()
        ]);

        if(class_exists('\Bindfy\DTO\Mappers\AbstractMapper')){
            $this->addReplaceImports('Bindfy\DTO\Mappers\AbstractMapper');
        }else{
            if(!class_exists('\App\Mappers\AbstractMapper')){
                $abstractMapperStub = FileHelper::getStubPath('mapper.abstract');

                // Write
                $this->writeFile(['app', 'Mappers', 'AbstractMapper.php'], $abstractMapperStub);
            }
        }

        $schema = [];
        $dto2ArrayIgnore = [];

        foreach($this->objGenDTO->getColumns() as $column){

            if(
                (isset($column->createdAt) && $column->createdAt === true) ||
                (isset($column->updatedAt) && $column->updatedAt === true) ||
                (isset($column->deletedAt) && $column->deletedAt === true)
            ) {
                continue;
            }

            $_c = ($column->alias ?? $column->name);

            $schema[] = "'" . $_c . "' => '" . $column->name . "',";

            if(
                (isset($column->fillable) && $column->fillable === false) ||
                (isset($column->primary) && $column->primary === true) ||
                $column->type == 'id'
            ){
                $dto2ArrayIgnore[] = "'$_c'";
            }
        }

        $ignoreList = '';
        if(count($dto2ArrayIgnore) > 0){
            $ignoreList = PHP_EOL . $this->loadList($dto2ArrayIgnore, '        ', ',') . PHP_EOL . '    ';
        }

        $this->addExtraReplaceParametersToReplace([
            '{{ schema }}' => $this->loadList($schema, '        ', ''),
            '{{ dto2ArrayIgnore }}' => $ignoreList,
        ]);

        // Write
        $this->writeFile(['app', 'Mappers', $this->objGenDTO->getMapper()], $this->replaceParameters($stub));
    }
}
