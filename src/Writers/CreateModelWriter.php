<?php /** @noinspection SpellCheckingInspection */

namespace JocelimJr\LaravelApiGenerator\Writers;

use JocelimJr\LaravelApiGenerator\DataTransferObject\ObjGenDTO;
use JocelimJr\LaravelApiGenerator\Helpers\FileHelper;

class CreateModelWriter extends AbstractWriter
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
        $createdAt = 'null';
        $updatedAt = 'null';
        $deletedAt = null;
        $primaryKey = '';
        $fillable = '';
        $fillableList = [];
        $this->addReplaceImports('Illuminate\Database\Eloquent\Model');
        $uses = [];
        $usesStr = '';
        $const = [];
        $constStr = '';
        $uuidLoaded = false;

        foreach($this->objGenDTO->getColumns() as $column){

            if(isset($column->type) && $column->type == 'uuid' && !$uuidLoaded){

                if(class_exists('\Bindfy\BaseService\Traits\UsesUuid')){
                    $this->addReplaceImports('Bindfy\BaseService\Traits\UsesUuid');
                }else{
                    $this->addReplaceImports('App\Traits\UsesUuid');

                    if(!class_exists('\App\Traits\UsesUuid')) {
                        $createUsesUUIDTraitWriter = new CreateUsesUUIDTraitWriter($this->objGenDTO);
                        $createUsesUUIDTraitWriter->write();
                    }
                }

                $uses[] = 'UsesUuid';

                $uuidLoaded = true;
            }

            if((isset($column->primary) && $column->primary === true) || $column->type == 'id'){
                $primaryKey = $column->name;

                continue;
            }

            if(
                $column->name == 'createdAt' ||
                ( isset($column->alias) && $column->alias == 'createdAt' ) ||
                ( isset($column->createdAt) && $column->createdAt === true )
            ){
                $const['CREATED_AT'] = $column->name;

                continue;
            }

            if(
                $column->name == 'updatedAt' ||
                ( isset($column->alias) && $column->alias == 'updatedAt' ) ||
                ( isset($column->updatedAt) && $column->updatedAt === true )
            ){
                $const['UPDATED_AT'] = $column->name;

                continue;
            }

            if(
                $column->name == 'deletedAt' ||
                ( isset($column->alias) && $column->alias == 'deletedAt' ) ||
                ( isset($column->deletedAt) && $column->deletedAt === true )
            ){
                $this->addReplaceImports('Illuminate\Database\Eloquent\SoftDeletes');
                $uses[] = 'SoftDeletes';
                $const['DELETED_AT'] = $column->name;

                continue;
            }

            if(!isset($column->fillable) || $column->fillable === true){
                $fillableList[] = $column->name;
            }
        }

        if(count($fillableList) > 0){
            $fillable = 'protected $fillable = [' . PHP_EOL;

            foreach($fillableList as $f){
                $fillable .= "        '$f'," . PHP_EOL;
            }

            $fillable .= '    ];';
        }

        if(count($uses) > 0){
            $usesStr = 'use ' . implode(', ', $uses) . ';' . PHP_EOL;
        }

        if(count($const) > 0){
            foreach($const as $k => $v){
                $constStr .= "    const $k = '$v';" . PHP_EOL;
            }
        }

        $stub = FileHelper::getStubPath('model');

        $this->addExtraReplaceParametersToReplace([
            '{{ uses }}' => $usesStr,
            '{{ const }}' => $constStr,
            '{{ primaryKey }}' => $primaryKey,
            '{{ fillable }}' => $fillable,
        ]);

        // Write
        $this->writeFile(['app', 'Models', $this->objGenDTO->getModel()], $this->replaceParameters($stub));
    }

}
