<?php
/** @noinspection PhpUnusedPrivateMethodInspection */
/** @noinspection PhpUnused */

namespace JocelimJr\LaravelApiGenerator\Writers;

use JocelimJr\LaravelApiGenerator\DataTransferObject\Column\AbstractColumn;
use JocelimJr\LaravelApiGenerator\DataTransferObject\Column\ColumnBoolean;
use JocelimJr\LaravelApiGenerator\DataTransferObject\Column\ColumnDate;
use JocelimJr\LaravelApiGenerator\DataTransferObject\Column\ColumnDateTime;
use JocelimJr\LaravelApiGenerator\DataTransferObject\Column\ColumnDateTimeTz;
use JocelimJr\LaravelApiGenerator\DataTransferObject\Column\ColumnDecimal;
use JocelimJr\LaravelApiGenerator\DataTransferObject\Column\ColumnDouble;
use JocelimJr\LaravelApiGenerator\DataTransferObject\Column\ColumnFloat;
use JocelimJr\LaravelApiGenerator\DataTransferObject\Column\ColumnId;
use JocelimJr\LaravelApiGenerator\DataTransferObject\Column\ColumnInteger;
use JocelimJr\LaravelApiGenerator\DataTransferObject\Column\ColumnSoftDeletes;
use JocelimJr\LaravelApiGenerator\DataTransferObject\Column\ColumnString;
use JocelimJr\LaravelApiGenerator\DataTransferObject\Column\ColumnTimestamp;
use JocelimJr\LaravelApiGenerator\DataTransferObject\Column\ColumnTimestamps;
use JocelimJr\LaravelApiGenerator\DataTransferObject\Column\ColumnUuid;
use JocelimJr\LaravelApiGenerator\DataTransferObject\JsonDefinitionsDTO;

class CreateMigrationWriter extends AbstractWriter
{
    public function __construct(JsonDefinitionsDTO $jsonDefinitionsDTO)
    {
        parent::__construct($jsonDefinitionsDTO);
    }

    public function write(): void
    {
        $this->writeFile(
            ['database', 'migrations', $this->jsonDefinitionsDTO->getFileName()->getMigration()],
            $this->replaceStubParameters('migration.create')
        );
    }

    /**
     * {{ migrationColumns }}
     * @return string
     */
    protected function migrationColumns(): string
    {
        $columns = '';

        /**
         * @var int $k
         * @var AbstractColumn $column
         */
        foreach($this->jsonDefinitionsDTO->getColumns() as $k => $column){
            $_last = $k == count($this->jsonDefinitionsDTO->getColumns()) - 1;
            $_call = 'column' . ($column->getType() !== null ? ucfirst($column->getType()) : 'String');

            $columns .= $k == 0 ? '' : '            ';
            $columns .= $this->$_call($column);

            if($column->getDefault()){

                $type = $column->getType() ?? 'string';

                $result = '->default(';

                if($type == 'string'){
                    $result .= '"' . $column->getDefault() . '"';
                }

                else if($type == 'boolean'){
                    $result .= ($column->getDefault() === true ? 'true' : 'false');
                }

                else{
                    $result .= $column->getDefault();
                }

                $columns .= $result . ')';
            }

            if($column->isPrimary() === true) $columns .= '->primary()';
            if($column->isAutoIncrement() === true) $columns .= '->autoIncrement()';
            if($column->isNullable() === true && $column->getType() !== 'id' && $column->isPrimary() !== true) $columns .= '->nullable()';
            if($column->isPrimary() === false && $column->isUnique() === true) $columns .= '->unique()';

            $columns .= ';' . ($_last ? '' : PHP_EOL);
        }

        return $columns;
    }

    private function columnBoolean(ColumnBoolean $column): string
    {
        return '$table->boolean("' . $column->getName() . '")';
    }

    private function columnDateTimeTz(ColumnDateTimeTz $column): string
    {
        // Default state
        if($column->getPrecision() == 0){
            return '$table->dateTimeTz("' . $column->getName() . '")';
        }

        return '$table->dateTimeTz("' . $column->getName() . '", ' . $column->getPrecision() . ')';
    }

    private function columnDateTime(ColumnDateTime $column): string
    {
        // Default state
        if($column->getPrecision() == 0){
            return '$table->dateTime("' . $column->getName() . '")';
        }

        return '$table->dateTime("' . $column->getName() . '", ' . $column->getPrecision() . ')';
    }

    private function columnDate(ColumnDate $column): string
    {
        return '$table->date("' . $column->getName() . '")';
    }

    private function columnDecimal(ColumnDecimal $column): string
    {
        // Default state
        if($column->getTotal() == 8 && $column->getPlaces() == 2 && !$column->isUnsigned()){
            return '$table->decimal("' . $column->getName() . '")';
        }

        return '$table->decimal("' . $column->getName() . '", ' . $column->getTotal() . ', ' . $column->getPlaces() . ', ' . ($column->isUnsigned() ? 'true' : 'false') . ')';
    }

    private function columnDouble(ColumnDouble $column): string
    {
        // Default state
        if($column->getTotal() == null && $column->getPlaces() == null && !$column->isUnsigned()){
            return '$table->double("' . $column->getName() . '")';
        }

        return '$table->double("' . $column->getName() . '", ' . $column->getTotal() . ', ' . $column->getPlaces() . ', ' . ($column->isUnsigned() ? 'true' : 'false') . ')';
    }

    private function columnFloat(ColumnFloat $column): string
    {
        // Default state
        if($column->getTotal() == 8 && $column->getPlaces() == 2 && !$column->isUnsigned()){
            return '$table->float("' . $column->getName() . '")';
        }

        return '$table->float("' . $column->getName() . '", ' . $column->getTotal() . ', ' . $column->getPlaces() . ', ' . ($column->isUnsigned() ? 'true' : 'false') . ')';
    }

    private function columnId(ColumnId $column): string
    {
        return '$table->id(' . ($column->getName() !== 'id' ? "'" . $column->getName() . "'" : "") . ')';
    }

    private function columnInteger(ColumnInteger $column): string
    {
        // Default state
        if(!$column->autoIncrement() && !$column->isUnsigned()){
            return '$table->integer("' . $column->getName() . '")';
        }

        return '$table->integer("' . $column->getName() . '", ' . ($column->autoIncrement() ? 'true' : 'false') . ', ' . ($column->isUnsigned() ? 'true' : 'false') . ')';
    }

    private function columnString(ColumnString $column): string
    {
        // Default state
        if($column->getLength() == null){
            return '$table->string("' . $column->getName() . '")';
        }

        return '$table->string("' . $column->getName() . '", ' . $column->getLength() . ')';
    }

    private function columnTimestamp(ColumnTimestamp $column): string
    {
        // Default state
        if($column->getPrecision() == 0){
            return '$table->timestamp("' . $column->getName() . '")';
        }

        return '$table->timestamp("' . $column->getName() . '", ' . $column->getPrecision() . ')';
    }

    private function columnUuid(ColumnUuid $column): string
    {
        return '$table->uuid(' . ($column->getName() !== 'uuid' ? "'" . $column->getName() . "'" : "") . ')';
    }

    private function columnTimestamps(ColumnTimestamps $column): string
    {
        return '$table->timestamps()';
    }

    private function columnSoftDeletes(ColumnSoftDeletes $column): string
    {
        if($column->getName() == 'deleted_at' && $column->getPrecision() == 0){
            return '$table->softDeletes()';
        }

        return '$table->timestamps("' . $column->getName() . '", ' . $column->getPrecision() . ')';
    }

}
