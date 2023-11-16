<?php /** @noinspection PhpUnusedPrivateMethodInspection */

namespace JocelimJr\LaravelApiGenerator\Writers;

use JocelimJr\LaravelApiGenerator\DataTransferObject\ObjGenDTO;
use JocelimJr\LaravelApiGenerator\Helpers\FileHelper;

class CreateMigrationWriter extends AbstractWriter
{

    /**
     * @param ObjGenDTO $objGenDTO
     */
    public function __construct(ObjGenDTO $objGenDTO)
    {
        parent::__construct($objGenDTO);
    }

    /**
     * @return void
     */
    public function write(): void
    {
        $stub = FileHelper::getStubPath('migration.create');

        $columns = '';

        foreach($this->objGenDTO->getColumns() as $k => $column){
            $_last = $k == count($this->objGenDTO->getColumns()) - 1;
            $_call = 'column' . (isset($column->type) ? ucfirst($column->type) : 'String');

            $columns .= $k == 0 ? '' : '            ';
            $columns .= $this->$_call($column);

            if(isset($column->default)){
                $columns .= $this->prepareDefaultValue($column);
            }

            if(isset($column->deletedAt) && $column->deletedAt === true){
                $column->nullable = true;
            }

            if(isset($column->primary) && $column->primary === true) $columns .= '->primary()';
            if(isset($column->autoIncrement) && $column->autoIncrement === true) $columns .= '->autoIncrement()';
            if(isset($column->nullable) && $column->nullable === true && $column->type !== 'id' && (!isset($column->primary) || $column->primary !== true)) $columns .= '->nullable()';

            if(
                (!isset($column->primary) || $column->primary === false) &&
                isset($column->unique) && $column->unique === true
            ) $columns .= '->unique()';

            $columns .= ';' . ($_last ? '' : PHP_EOL);
        }

        $this->addExtraReplaceParametersToReplace([
            '{{ columns }}' => $columns,
        ]);

        $newMigrationFile = $this->objGenDTO->getPrefixDateMigration() . '_create_' . strtolower($this->objGenDTO->getApiName()) . '_table.php';

        // Write
        $this->writeFile(['database', 'migrations', $newMigrationFile], $this->replaceParameters($stub));
    }

    /**
     * @boolean
     *
     * @param mixed $column
     * @return string
     */
    private function columnBoolean(mixed $column): string
    {
        return $this->mount('boolean', ["'" . $column->name . "'"]);
    }

    /**
     * @dateTimeTz
     *
     * @param mixed $column
     * @return string
     */
    private function columnDateTimeTz(mixed $column): string
    {
        $params = ["'" . $column->name . "'"];

        if(isset($column->precision) && is_int($column->precision) && $column->precision > 0){
            $params[] = $column->precision;
        }

        return $this->mount('dateTimeTz', $params);
    }

    /**
     * @dateTime
     *
     * @param mixed $column
     * @return string
     */
    private function columnDateTime(mixed $column): string
    {
        $params = ["'" . $column->name . "'"];

        if(isset($column->precision) && is_int($column->precision) && $column->precision > 0){
            $params[] = $column->precision;
        }

        return $this->mount('dateTime', $params);
    }

    /**
     * @date
     *
     * @param mixed $column
     * @return string
     */
    private function columnDate(mixed $column): string
    {
        return $this->mount('date', ["'" . $column->name . "'"]);
    }

    /**
     * @decimal
     *
     * @param mixed $column
     * @return string
     */
    private function columnDecimal(mixed $column): string
    {
        $defaultTotal = '8';
        $defaultPlaces = '2';
        $defaultUnsigned = 'false';

        return $this->mount('decimal', $this->getDoubleFloatParams($column, $defaultTotal, $defaultPlaces, $defaultUnsigned));
    }

    /**
     * @double
     *
     * @param mixed $column
     * @return string
     */
    private function columnDouble(mixed $column): string
    {
        $defaultTotal = 'null';
        $defaultPlaces = 'null';
        $defaultUnsigned = 'false';

        return $this->mount('double', $this->getDoubleFloatParams($column, $defaultTotal, $defaultPlaces, $defaultUnsigned));
    }

    /**
     * @float
     *
     * @param mixed $column
     * @return string
     */
    private function columnFloat(mixed $column): string
    {
        $defaultTotal = '8';
        $defaultPlaces = '2';
        $defaultUnsigned = 'false';

        return $this->mount('float', $this->getDoubleFloatParams($column, $defaultTotal, $defaultPlaces, $defaultUnsigned));
    }

    /**
     * @id
     *
     * @param mixed $column
     * @return string
     */
    private function columnId(mixed $column): string
    {
        return $this->mount('id', ["'" . $column->name . "'"]);
    }

    /**
     * @integer
     *
     * @param mixed $column
     * @return string
     */
    private function columnInteger(mixed $column): string
    {
        $params = ["'" . $column->name . "'"];

        $incAutoIncrement = false;
        if(isset($column->autoIncrement) && $column->autoIncrement === true){
            $incAutoIncrement = true;
            $params[] = 'true';
        }

        if(isset($column->unsigned) && $column->unsigned === true){
            if(!$incAutoIncrement){
                $params[] = 'false';
            }

            $params[] = 'true';
        }

        return $this->mount('integer', $params);
    }

    /**
     * @string
     *
     * @param mixed $column
     * @return string
     */
    private function columnString(mixed $column): string
    {
        $params = ["'" . $column->name . "'"];

        if(isset($column->length)){
            $params[] = $column->length;
        }

        return $this->mount('string', $params);
    }

    /**
     * @timestamp
     *
     * @param mixed $column
     * @return string
     */
    private function columnTimestamp(mixed $column): string
    {
        $params = ["'" . $column->name . "'"];

        if(isset($column->precision) && is_int($column->precision) && $column->precision > 0){
            $params[] = $column->precision;
        }

        return $this->mount('timestamp', $params);
    }

    /**
     * @uuid
     *
     * @param mixed $column
     * @return string
     */
    private function columnUuid(mixed $column): string
    {
        return $this->mount('uuid', ["'" . $column->name . "'"]);
    }

    /**
     * @param mixed $column
     * @return string
     */
    private function prepareDefaultValue(mixed $column): string
    {
        $type = $column->type ?? 'string';

        $result = '->default(';

        if($type == 'string'){
            $result .= "'" . $column->default . "'";
        }

        else if($type == 'boolean'){
            $result .= ($column->default === true ? 'true' : 'false');
        }

        else{
            $result .= $column->default;
        }

        return $result . ')';
    }

    /**
     * @param mixed $column
     * @param string $defaultTotal
     * @param string $defaultPlaces
     * @param string $defaultUnsigned
     * @return string[]
     */
    private function getDoubleFloatParams(mixed $column, string $defaultTotal = 'null', string $defaultPlaces = 'null', string $defaultUnsigned = 'false'): array
    {
        if(property_exists($column, 'total')){
            $column->total = $column->total === null ? 'null' : (string) $column->total;
        }else{
            $column->total = $defaultTotal;
        }

        if(property_exists($column, 'places')){
            $column->places = $column->places === null ? 'null' : (string) $column->places;
        }else{
            $column->places = $defaultPlaces;
        }

        if(property_exists($column, 'unsigned')){
            $column->unsigned = $column->unsigned === false ? 'false' : 'true';
        }else{
            $column->unsigned = $defaultUnsigned;
        }

        $params = ["'" . $column->name . "'"];
        $_pTotal = false;
        $_pPlaces = false;

        if ($column->total !== $defaultTotal) {
            $_pTotal = true;
            $params[] = $column->total;
        }

        if ($column->places !== $defaultPlaces) {
            if (!$_pTotal) $params[] = $defaultTotal;

            $_pPlaces = true;
            $params[] = $column->places;
        }

        if ($column->unsigned !== $defaultUnsigned) {
            if (!$_pTotal) $params[] = $defaultTotal;
            if (!$_pPlaces) $params[] = $defaultPlaces;

            $params[] = $column->unsigned;
        }

        return $params;
    }

    /**
     * @param String $name
     * @param array $params
     * @return String
     */
    private function mount(String $name, array $params): String
    {
        return '$table->' . $name . '(' . implode(', ', $params). ')';
    }
}
