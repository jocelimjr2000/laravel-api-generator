<?php /** @noinspection SpellCheckingInspection */

namespace JocelimJr\LaravelApiGenerator\Writers;

use JocelimJr\LaravelApiGenerator\DataTransferObject\ObjGenDTO;
use JocelimJr\LaravelApiGenerator\Helpers\FileHelper;
use JocelimJr\LaravelApiGenerator\Helpers\ParametersHelper;
use Exception;

class CreateRoutesWriter extends AbstractWriter
{

    /**
     * @param ObjGenDTO $objGenDTO
     */
    public function __construct(ObjGenDTO $objGenDTO)
    {
        parent::__construct($objGenDTO);
    }

    /**
     * @throws Exception
     */
    public function write()
    {
        $stub = FileHelper::getStubPath('routes');

        $routes = [];
        $swaggerCreateRequest = [];
        $swaggerResponse = [];
        $swaggerUpdateRequest = [];
        $swaggerRequiredCreate = [];
        $swaggerRequiredUpdate = [];

        foreach($this->objGenDTO->getColumns() as $column){

            if(
                (isset($column->createdAt) && $column->createdAt === true) ||
                (isset($column->updatedAt) && $column->updatedAt === true) ||
                (isset($column->deletedAt) && $column->deletedAt === true)
            ) {
                continue;
            }

            $_c = ($column->alias ?? $column->name);
            $_type = ParametersHelper::getParameterType(ref: $column->type, swagger: true);

            $example = ParametersHelper::generateDataByType($column->type, "", $column->example ?? null);
            $description = $column->description ?? '';

            $swaggerResponse[] = '@OA\Property(property="' . $_c . '", type="' . $_type . '", example="' . $example . '", description="' . $description . '")';

            if(isset($column->validations) && in_array('required', $column->validations)){
                $swaggerRequiredCreate[] = '"' . $_c . '"';
                $swaggerRequiredUpdate[] = '"' . $_c . '"';
            }else{
                if(isset($column->validationsOnCreate) && in_array('required', $column->validationsOnCreate)){
                    $swaggerRequiredCreate[] = '"' . $_c . '"';
                }

                if(isset($column->validationsOnUpdate) && in_array('required', $column->validationsOnUpdate)){
                    $swaggerRequiredUpdate[] = '"' . $_c . '"';
                }
            }

            if(isset($column->fillable) && $column->fillable === false) {
                continue;
            }

            if(!((isset($column->primary) && $column->primary === true) || $column->type == 'id')) {
                $swaggerCreateRequest[] = '@OA\Property(property="' . $_c . '", type="' . $_type . '", example="' . $example . '", description="' . $description . '")';
            }

            $swaggerUpdateRequest[] = '@OA\Property(property="' . $_c . '", type="' . $_type . '", example="' . $example . '", description="' . $description . '")';
        }

        if($this->objGenDTO->isWriteApiFindAll()) $routes[] = $this->writeRouteFindAll();
        if($this->objGenDTO->isWriteApiFindById()) $routes[] = $this->writeRouteFindById();
        if($this->objGenDTO->isWriteApiCreate()) $routes[] = $this->writeRouteCreate();
        if($this->objGenDTO->isWriteApiUpdate()) $routes[] = $this->writeRouteUpdate();
        if($this->objGenDTO->isWriteApiDelete()) $routes[] = $this->writeRouteDelete();

        $this->addExtraReplaceParametersToReplace([
            '{{ tags }}' => implode(",", array_map(function($v){
                return '"' . $v . '"';
            }, $this->objGenDTO->getTags())),
            '{{ routes }}' => $this->loadList($routes, '', ''),
            '{{ swaggerCreateRequest }}' => $this->loadList($swaggerCreateRequest, '         *                  ', ','),
            '{{ swaggerUpdateRequest }}' => $this->loadList($swaggerUpdateRequest, '         *                  ', ','),
            '{{ swaggerResponse }}' => $this->loadList($swaggerResponse, '         *              ', ','),
            '{{ swaggerResponseArr }}' => $this->loadList($swaggerResponse, '         *                  ', ','),
            '{{ swaggerRequiredCreate }}' => implode("," , $swaggerRequiredCreate),
            '{{ swaggerRequiredUpdate }}' => implode("," , $swaggerRequiredUpdate),
        ]);

        // Write
        $this->writeFile(['routes', $this->objGenDTO->getRoutesFileName()], $this->replaceParameters($stub));

        $this->objGenDTO->addError('Route file (\'' . $this->objGenDTO->getRoutesFileName() . '.php\') not included in \'RoutesServiceProvider.php\'');
    }

    private function writeRouteFindAll(): string
    {
        return FileHelper::getStubPath('content.routes.findAll');
    }

    private function writeRouteFindById(): string
    {
        return FileHelper::getStubPath('content.routes.findById');
    }

    private function writeRouteCreate(): string
    {
        return FileHelper::getStubPath('content.routes.create');
    }

    private function writeRouteUpdate(): string
    {
        return FileHelper::getStubPath('content.routes.update');
    }

    private function writeRouteDelete(): string
    {
        return FileHelper::getStubPath('content.routes.delete');
    }
}
