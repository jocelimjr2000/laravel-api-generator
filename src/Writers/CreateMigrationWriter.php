<?php
/** @noinspection PhpUnusedPrivateMethodInspection */
/** @noinspection PhpUnused */

namespace JocelimJr\LaravelApiGenerator\Writers;

use JocelimJr\LaravelApiGenerator\Classes\Column\ColumnBoolean;
use JocelimJr\LaravelApiGenerator\Classes\Column\ColumnDate;
use JocelimJr\LaravelApiGenerator\Classes\Column\ColumnDateTime;
use JocelimJr\LaravelApiGenerator\Classes\Column\ColumnDateTimeTz;
use JocelimJr\LaravelApiGenerator\Classes\Column\ColumnDecimal;
use JocelimJr\LaravelApiGenerator\Classes\Column\ColumnDouble;
use JocelimJr\LaravelApiGenerator\Classes\Column\ColumnFloat;
use JocelimJr\LaravelApiGenerator\Classes\Column\ColumnId;
use JocelimJr\LaravelApiGenerator\Classes\Column\ColumnInteger;
use JocelimJr\LaravelApiGenerator\Classes\Column\ColumnString;
use JocelimJr\LaravelApiGenerator\Classes\Column\ColumnTimestamp;
use JocelimJr\LaravelApiGenerator\Classes\Column\ColumnUUID;
use JocelimJr\LaravelApiGenerator\DataTransferObject\ColumnDTO;
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

    protected function migrationColumns(): string
    {
        $columns = '';

        /**
         * @var int $k
         * @var ColumnDTO $column
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
                    $result .= "'" . $column->getDefault() . "'";
                }

                else if($type == 'boolean'){
                    $result .= ($column->getDefault() === true ? 'true' : 'false');
                }

                else{
                    $result .= $column->getDefault();
                }

                $columns .= $result . ')';
            }

            if($column->isDeletedAt() === true) $column->setNullable(true);
            if($column->isPrimary() === true) $columns .= '->primary()';
            if($column->isAutoIncrement() === true) $columns .= '->autoIncrement()';
            if($column->isNullable() === true && $column->getType() !== 'id' && $column->isPrimary() !== true) $columns .= '->nullable()';
            if($column->isPrimary() === false && $column->isUnique() === true) $columns .= '->unique()';

            $columns .= ';' . ($_last ? '' : PHP_EOL);
        }

        return $columns;
    }

    /**
     * @boolean
     *
     * @param ColumnBoolean $column
     * @return string
     */
    private function columnBoolean(ColumnBoolean $column): string
    {
        return '$table->boolean("' . $column->getName() . '")';
    }

    /**
     * @dateTimeTz
     *
     * @param ColumnDateTimeTz $column
     * @return string
     */
    private function columnDateTimeTz(ColumnDateTimeTz $column): string
    {
        // Default state
        if($column->getPrecision() == 0){
            return '$table->dateTimeTz("' . $column->getName() . '")';
        }

        return '$table->dateTimeTz("' . $column->getName() . '", ' . $column->getPrecision() . ')';
    }

    /**
     * @dateTime
     *
     * @param ColumnDateTime $column
     * @return string
     */
    private function columnDateTime(ColumnDateTime $column): string
    {
        // Default state
        if($column->getPrecision() == 0){
            return '$table->dateTime("' . $column->getName() . '")';
        }

        return '$table->dateTime("' . $column->getName() . '", ' . $column->getPrecision() . ')';
    }

    /**
     * @date
     *
     * @param ColumnDate $column
     * @return string
     */
    private function columnDate(ColumnDate $column): string
    {
        return '$table->date("' . $column->getName() . '")';
    }

    /**
     * @decimal
     *
     * @param ColumnDecimal $column
     * @return string
     */
    private function columnDecimal(ColumnDecimal $column): string
    {
        // Default state
        if($column->getTotal() == 8 && $column->getPlaces() == 2 && !$column->isUnsigned()){
            return '$table->decimal("' . $column->getName() . '")';
        }

        return '$table->decimal("' . $column->getName() . '", ' . $column->getTotal() . ', ' . $column->getPlaces() . ', ' . ($column->isUnsigned() ? 'true' : 'false') . ')';
    }

    /**
     * @double
     *
     * @param ColumnDouble $column
     * @return string
     */
    private function columnDouble(ColumnDouble $column): string
    {
        // Default state
        if($column->getTotal() == null && $column->getPlaces() == null && !$column->isUnsigned()){
            return '$table->double("' . $column->getName() . '")';
        }

        return '$table->double("' . $column->getName() . '", ' . $column->getTotal() . ', ' . $column->getPlaces() . ', ' . ($column->isUnsigned() ? 'true' : 'false') . ')';
    }

    /**
     * @float
     *
     * @param ColumnFloat $column
     * @return string
     */
    private function columnFloat(ColumnFloat $column): string
    {
        // Default state
        if($column->getTotal() == 8 && $column->getPlaces() == 2 && !$column->isUnsigned()){
            return '$table->float("' . $column->getName() . '")';
        }

        return '$table->float("' . $column->getName() . '", ' . $column->getTotal() . ', ' . $column->getPlaces() . ', ' . ($column->isUnsigned() ? 'true' : 'false') . ')';
    }

    /**
     * @id
     *
     * @param ColumnId $column
     * @return string
     */
    private function columnId(ColumnId $column): string
    {
        return '$table->id(' . ($column->getName() !== 'id' ? "'" . $column->getName() . "'" : "") . ')';
    }

    /**
     * @integer
     *
     * @param ColumnInteger $column
     * @return string
     */
    private function columnInteger(ColumnInteger $column): string
    {
        // Default state
        if(!$column->isAutoIncrement() && !$column->isUnsigned()){
            return '$table->integer("' . $column->getName() . '")';
        }

        return '$table->integer("' . $column->getName() . '", ' . ($column->isAutoIncrement() ? 'true' : 'false') . ', ' . ($column->isUnsigned() ? 'true' : 'false') . ')';
    }

    /**
     * @string
     *
     * @param ColumnString $column
     * @return string
     */
    private function columnString(ColumnString $column): string
    {
        // Default state
        if($column->getLength() == null){
            return '$table->string("' . $column->getName() . '")';
        }

        return '$table->string("' . $column->getName() . '", ' . $column->getLength() . ')';
    }

    /**
     * @timestamp
     *
     * @param ColumnTimestamp $column
     * @return string
     */
    private function columnTimestamp(ColumnTimestamp $column): string
    {
        // Default state
        if($column->getPrecision() == 0){
            return '$table->timestamp("' . $column->getName() . '")';
        }

        return '$table->timestamp("' . $column->getName() . '", ' . $column->getPrecision() . ')';
    }

    /**
     * @uuid
     *
     * @param ColumnUUID $column
     * @return string
     */
    private function columnUuid(ColumnUUID $column): string
    {
        return '$table->uuid("' . $column->getName() . '")';
    }

}
