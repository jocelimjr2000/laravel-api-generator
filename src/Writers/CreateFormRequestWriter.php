<?php

namespace JocelimJr\LaravelApiGenerator\Writers;

use JocelimJr\LaravelApiGenerator\DataTransferObject\ObjGenDTO;
use JocelimJr\LaravelApiGenerator\Helpers\FileHelper;

/**
 * https://laravel.com/docs/9.x/validation#available-validation-rules
 */
class CreateFormRequestWriter extends AbstractWriter
{

    /**
     * @param ObjGenDTO $objGenDTO
     */
    public function __construct(ObjGenDTO $objGenDTO)
    {
        parent::__construct($objGenDTO);
    }

    public function write(): void
    {
        $validationsOnCreate = [];
        $validationsOnUpdate = [];
        $validationsOnDelete = [];

        foreach ($this->objGenDTO->getColumns() as $column) {
            if (isset($column->validations)) {
                $validationsOnCreate[$column->alias ?? $column->name] = $column->validations;
                $validationsOnUpdate[$column->alias ?? $column->name] = $column->validations;
                $validationsOnDelete[$column->alias ?? $column->name] = $column->validations;
            }

            if (isset($column->validationsOnCreate)) {
                $validationsOnCreate[$column->alias ?? $column->name] = array_merge($validationsOnCreate[$column->alias ?? $column->name] ?? [], $column->validationsOnCreate);
            }

            if (isset($column->validationsOnUpdate)) {
                $validationsOnUpdate[$column->alias ?? $column->name] = array_merge($validationsOnUpdate[$column->alias ?? $column->name] ?? [], $column->validationsOnUpdate);
            }

            if (isset($column->validationsOnDelete)) {
                $validationsOnDelete[$column->alias ?? $column->name] = array_merge($validationsOnDelete[$column->alias ?? $column->name] ?? [], $column->validationsOnDelete);
            }
        }

        $this->formRequest($this->objGenDTO->getFormCreateRequest(), $validationsOnCreate, 'create');
        $this->formRequest($this->objGenDTO->getFormUpdateRequest(), $validationsOnUpdate, 'update');
        $this->formRequest($this->objGenDTO->getFormDeleteRequest(), $validationsOnDelete, 'delete');
    }

    /**
     * @param string $formRequest
     * @param array $validations
     * @param string $formRequestMode
     * @return void
     */
    private function formRequest(string $formRequest, array $validations, string $formRequestMode): void
    {
        $values = [];

        foreach ($validations as $k => $v) {
            $v = array_map(function ($values) {
                return "'$values'";
            }, $v);

            $values[] = "'$k' => [
" . $this->loadList($v, '                ', ',') . "
            ]";
        }

        if(($formRequestMode == 'update' || $formRequestMode == 'delete') && count($values) == 0){
            $_v = [];
            $_v[] = "'required'";
            if($this->objGenDTO->getIdType() == 'uuid'){
                $_v[] = "'uuid'";
            }
            $_v[] = "'exists:{{ table }},{{ idName }}'";

            $values[] = "'{{ idParameter }}' => [" . implode(", " , $_v) . "]";
        }

        $stub = FileHelper::getStubPath('formRequest');

        $this->addExtraReplaceParametersToReplace([
            '{{ formRequest }}' => $formRequest,
            '{{ validations }}' => $this->loadList($values, '            ', ','),
            '{{ docParameters }}' => $this->getDocParameters($formRequestMode),
        ]);

        // Write
        $this->writeFile(['app', 'Http', 'Requests', $formRequest], $this->replaceParameters($stub));
    }

    private function getDocParameters(string $mode): string
    {
        $properties = '';

        foreach($this->objGenDTO->getColumns() as $column){

            if(
                (isset($column->createdAt) && $column->createdAt === true) ||
                (isset($column->updatedAt) && $column->updatedAt === true) ||
                (isset($column->deletedAt) && $column->deletedAt === true) ||
                (
                    $mode == 'create' && ((isset($column->primary) && $column->primary === true) || $column->type == 'id')
                ) ||
                (
                    $mode == 'delete' && !((isset($column->primary) && $column->primary === true) || $column->type == 'id')
                )
            ) continue;

            $properties .= ' * @property mixed $' . ($column->alias ?? $column->name) . PHP_EOL;
        }

        return '/**
' . $properties . ' */';
    }

}
