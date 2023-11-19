<?php
/** @noinspection PhpUnused */
/** @noinspection SpellCheckingInspection */

namespace JocelimJr\LaravelApiGenerator\Writers;

use JocelimJr\LaravelApiGenerator\DataTransferObject\Column\AbstractColumn;
use JocelimJr\LaravelApiGenerator\DataTransferObject\JsonDefinitionsDTO;

class CreateModelWriter extends AbstractWriter
{
    private array $fillableList = [];
    private array $uses = [];
    private array $primaryKey = [];
    private bool $constCreatedAtIsNull = true;
    private bool $constUpdatedAtIsNull = true;
    private ?string $constCreatedAtName = null;
    private ?string $constUpdatedAtName = null;
    private ?string $constDeletedAtName = null;

    public function __construct(JsonDefinitionsDTO $jsonDefinitionsDTO)
    {
        parent::__construct($jsonDefinitionsDTO);
    }

    public function write(): void
    {
        $this->addImportsToReplace('Illuminate\Database\Eloquent\Model');

        /** @var AbstractColumn $column */
        foreach($this->jsonDefinitionsDTO->getColumns() as $column){

            // Primary Key
            if($column->getType() == 'id' || $column->isPrimary()){
                $this->primaryKey[] = $column;
            }

            // Primary Key (uuid)
            if($column->isPrimary() && $column->getType() == 'uuid'){
                $this->addImportsToReplace('Illuminate\Database\Eloquent\Concerns\HasUuids');
                $this->uses[] = 'HasUuids';
            }

            // Add to Fillable list
            if($column->isFillable()){
                $this->fillableList[] = $column->getName();
            }

            // Softdelete
            if($column->getType() == 'softDeletes'){
                $this->addImportsToReplace('Illuminate\Database\Eloquent\SoftDeletes');
                $this->uses[] = 'SoftDeletes';
            }

            if($column->getType() == 'timestamps'){
                $this->constCreatedAtIsNull = false;
                $this->constUpdatedAtIsNull = false;
            }else{
                if($column->getType() == 'timestamp' && $column->isCreatedAt()){
                    $this->constCreatedAtIsNull = false;
                }

                if($column->getType() == 'timestamp' && $column->isUpdatedAt()){
                    $this->constUpdatedAtIsNull = false;
                }
            }
        }

        $this->writeFile(
            ['app', 'Models', $this->jsonDefinitionsDTO->getFileName()->getModel()],
            $this->replaceStubParameters('model')
        );
    }

    /**
     * {{ modelContent }}
     * @return string
     */
    protected function modelContent(): string
    {
        $modelContent = '';

        // Uses
        if(count($this->uses) > 0){
            $modelContent .= '    use ' . implode(', ', $this->uses) . ';';
        }

        // Const
        if($this->constCreatedAtIsNull){
            $modelContent .= PHP_EOL . '    const CREATED_AT = null;';
        }else if($this->constCreatedAtName){
            $modelContent .= PHP_EOL . '    const CREATED_AT = "' . $this->constCreatedAtName . '";';
        }

        if($this->constUpdatedAtIsNull){
            $modelContent .= PHP_EOL . '    const UPDATED_AT = null;';
        }else if($this->constUpdatedAtName){
            $modelContent .= PHP_EOL . '    const UPDATED_AT = "' . $this->constUpdatedAtName . '";';
        }

        if($this->constDeletedAtName){
            $modelContent .= PHP_EOL . '    const DELETED_AT = "' . $this->constDeletedAtName . '";';
        }

        // Table ref
        if(
            $this->jsonDefinitionsDTO->getTable() !== null &&
            $this->jsonDefinitionsDTO->getTable() !== lcfirst($this->jsonDefinitionsDTO->getFileName()->getModel())
        ){
            $modelContent .= '    protected $table = \'' . $this->jsonDefinitionsDTO->getTable() . '\';';
        }

        // Primary Key
        if(count($this->primaryKey) > 1){

        }else{
            $pkColumnName = $this->primaryKey[0]->getName();

            if($pkColumnName !== 'id'){
                $modelContent .= PHP_EOL;
                $modelContent .= '    protected $primaryKey = \'' . $pkColumnName . '\';';
            }
        }

        // Fillable
        $fillable = $this->fillableListToStr();

        if($fillable) {
            $modelContent .= PHP_EOL . PHP_EOL;
            $modelContent .= $fillable;
        }

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
