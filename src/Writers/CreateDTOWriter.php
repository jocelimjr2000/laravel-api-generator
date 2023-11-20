<?php /** @noinspection SpellCheckingInspection */

namespace JocelimJr\LaravelApiGenerator\Writers;

use JocelimJr\LaravelApiGenerator\DataTransferObject\JsonDefinitionsDTO;
use JocelimJr\LaravelApiGenerator\Helpers\FileHelper;
use JocelimJr\LaravelApiGenerator\Helpers\ParametersHelper;

class CreateDTOWriter extends AbstractWriter
{
    public function __construct(JsonDefinitionsDTO $jsonDefinitionsDTO)
    {
        parent::__construct($jsonDefinitionsDTO);
    }

    public function write(): void
    {
        if(!class_exists('\App\DataTransferObject\AbstractDTO')){
            $this->writeFile(
                ['app', 'DataTransferObject', 'AbstractDTO.php'],
                $this->replaceStubParameters('dto.abstract')
            );
        }

//        $this->replaceParameters();

//        $this->addExtraReplaceParametersToReplace([
//            '{{ parameters }}' => $this->loadList($this->loadParameters(), '    '),
//            '{{ methods }}' => $this->loadList($this->loadGettersAndSetters(), '', ''),
//        ]);


        // Write
        $this->writeFile(
            ['app', 'DataTransferObject', $this->jsonDefinitionsDTO->getFileName()->getDto()],
            $this->replaceStubParameters('dto')
        );
    }

    /**
     * {{ parameters }}
     * @return string
     */
    protected function parameters(): string
    {

    }

//    private function loadParameters(): array
//    {
//        $parameters = [];
//
//        foreach ($this->objGenDTO->getColumns() as $column) {
//            if(
//                (isset($column->createdAt) && $column->createdAt === true) ||
//                (isset($column->updatedAt) && $column->updatedAt === true) ||
//                (isset($column->deletedAt) && $column->deletedAt === true)
//            ) {
//                continue;
//            }
//
//            $_param = ($column->alias ?? $column->name);
//            $_type = ParametersHelper::getParameterType($column->type);
//
//            $parameters[] = 'private ' . $_type . ' $' . $_param . ' = null';
//        }
//
//        return $parameters;
//    }
//
//    private function loadGettersAndSetters(): array
//    {
//        $methods = [];
//
//        foreach ($this->objGenDTO->getColumns() as $column) {
//            if(
//                (isset($column->createdAt) && $column->createdAt === true) ||
//                (isset($column->updatedAt) && $column->updatedAt === true) ||
//                (isset($column->deletedAt) && $column->deletedAt === true)
//            ) {
//                continue;
//            }
//
//            $_type = ParametersHelper::getParameterType($column->type, false);
//            $methods[] = $this->prepareGetterAndSetter($_type, $column->alias ?? $column->name);
//        }
//
//        return $methods;
//    }
//
//    private function prepareGetterAndSetter(string $type, string $param): string
//    {
//        $dto = $this->objGenDTO->getDto();
//
//        return '
//    /**
//     * @return ' . $type . '|null
//     */
//    public function get' . ucfirst($param) . '(): ?' . $type . '
//    {
//        return $this->' . $param . ';
//    }
//
//    /**
//     * @param ' . $type . '|null $' . $param . '
//     * @return ' . $dto . '
//     */
//    public function set' . ucfirst($param) . '(?' . $type . ' $' . $param . '): ' . $dto . '
//    {
//        $this->' . $param . ' = $' . $param . ';
//        return $this;
//    }';
//    }
}
