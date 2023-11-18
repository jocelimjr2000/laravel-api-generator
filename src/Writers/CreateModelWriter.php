<?php
/** @noinspection PhpUnused */
/** @noinspection SpellCheckingInspection */

namespace JocelimJr\LaravelApiGenerator\Writers;

use JocelimJr\LaravelApiGenerator\DataTransferObject\ColumnDTO;
use JocelimJr\LaravelApiGenerator\DataTransferObject\JsonDefinitionsDTO;

class CreateModelWriter extends AbstractWriter
{
    private array $fillableList = [];

    public function __construct(JsonDefinitionsDTO $jsonDefinitionsDTO)
    {
        parent::__construct($jsonDefinitionsDTO);
    }

    public function write(): void
    {
        $this->addImportsToReplace([
            'Illuminate\Database\Eloquent\Model'
        ]);

        $this->writeFile(
            ['app', 'Models', $this->jsonDefinitionsDTO->getFileName()->getModel()],
            $this->replaceStubParameters('model')
        );
    }

    protected function modelContent(): string
    {
        $modelContent = '';

        // Table ref
        if($this->jsonDefinitionsDTO->getTable() !== null){
            $modelContent .= '    protected $table = \'' . $this->jsonDefinitionsDTO->getTable() . '\';';
        }

        // Primary Key ref
        if(
            $this->jsonDefinitionsDTO->getPrimaryKey() !== null &&
            $this->jsonDefinitionsDTO->getPrimaryKey()->getName() !== null &&
            $this->jsonDefinitionsDTO->getPrimaryKey()->getName() !== 'id'
        ){
            $modelContent .= PHP_EOL;
            $modelContent .= '    protected $primaryKey = \'' . $this->jsonDefinitionsDTO->getPrimaryKey()->getName() . '\';';
        }

        /** @var ColumnDTO $column */
        foreach($this->jsonDefinitionsDTO->getColumns() as $column){

            // Add to Fillable list
            if(!isset($column->fillable) || $column->fillable === true){
                $this->fillableList[] = $column->getName();
            }
        }

        $modelContent .= PHP_EOL . PHP_EOL;
        $modelContent .= $this->fillableListToStr();

        return $modelContent;
    }

    /**
     * Convert Fillable list to String
     *
     * @return string
     */
    private function fillableListToStr(): string
    {
        $fillable = '';

        if(count($this->fillableList) > 0){
            $fillable .= '    protected $fillable = [' . PHP_EOL;

            foreach($this->fillableList as $f){
                $fillable .= "        '$f'," . PHP_EOL;
            }

            $fillable .= '    ];';
        }

        return $fillable;
    }
}
