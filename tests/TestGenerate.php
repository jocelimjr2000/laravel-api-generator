<?php /** @noinspection SpellCheckingInspection */

require(__DIR__ . '/../vendor/autoload.php');

use JocelimJr\LaravelApiGenerator\Service\GeneratorService;

$generatorService = new GeneratorService();

function delTree($dir): bool
{
    if(!file_exists($dir)) return false;

    $files = array_diff(scandir($dir), array('.', '..'));
    foreach ($files as $file) {
        (is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file");
    }
    return rmdir($dir);
}

$path = __DIR__ . DIRECTORY_SEPARATOR . 'code';

$data = [
    "moduleName" => "test",
//    "serviceLayer" => true,
//    "repositoryLayer" => true,
//    "mapperLayer" => true,
//    "dtoLayer" => true,
    "createMigration" => true,
    "createModel" => true,
    "createRepository" => true,
    "createMapper" => true,
    "createDTO" => true,
    "createService" => true,
    "createController" => true,
    "createRoutes" => true,
    "writeApiFindAll" => true,
    "writeApiFindById" => true,
    "writeApiCreate" => true,
    "writeApiUpdate" => true,
    "writeApiDelete" => true,
    "apiPrefix" => null,
    "prefixDateMigration" => null,
    "model" => null,
    "repository" => null,
    "repositoryInterface" => null,
    "mapper" => null,
    "dto" => null,
    "service" => null,
    "controller" => null,
    "table" => "TEST",
    "basePath" => $path,
    "columns" => [
//        [
//            "primary" => true,
//            "alias2" => "uuid_alias",
//            "name" => "COL_UUID",
//            "type" => "uuid",
//            "fillable" => false,                          // (Required: false, Default: false)
////            "validations" => [],                          // Validations on Create, Update and Delete
////            "validationsOnCreate" => [],                  // Validations on Create
////            "validationsOnUpdate" => [],                  // Validations on Update
////            "validationsOnDelete" => []                   // Validations on Delete
//        ],
        [
            "alias" => "id_alias",
            "name" => "COL_ID",
            "type" => "id",
            "nullable" => true,
            "validations" => ['required'],
            "validationsOnCreate" => ['uuid'],
        ],
        [
            "alias" => "string_alias",
            "name" => "COL_STRING",
            "type" => "string",
            "length" => "50",
            "nullable" => true
        ],
        [
            "alias" => "boolean_alias",
            "name" => "COL_BOOLEAN",
            "type" => "boolean",
            "default" => true
        ],
        [
            "alias" => "decimal_alias",
            "name" => "COL_DECIMAL",
            "type" => "decimal",
            "total" => null,
            "places" => 2,
            "unsigned" => true,
            "nullable" => true
        ],
        [
            "alias" => "double_alias",
            "name" => "COL_DOUBLE",
            "type" => "double",
            "total" => null,
            "places" => 5,
            "unsigned" => false,
            "nullable" => true
        ],
        [
            "alias" => "float_alias",
            "name" => "COL_FLOAT",
            "type" => "float",
            "nullable" => true
        ],
        [
            "alias" => "createdAt",
            "name" => "COL_CADASTRADO",
            "type" => "timestamp",
            "precision" => 0,
            "createdAt" => true
        ],
        [
            "alias" => "updatedAt",
            "name" => "COL_ALTERADO",
            "type" => "dateTime",
            "precision" => 0,
            "updatedAt" => true
        ]
    ]
];

delTree($path);

mkdir($path, 777, true);

$data["serviceLayer"] = false;
$data["repositoryLayer"] = true;
$data["mapperLayer"] = false;
$data["dtoLayer"] = true;

$generatorService->generate(json_decode(json_encode($data)));
exit;

for ($i = 1; $i <= 6; $i++){

    $data["moduleName"] = "test" . $i;

    if($i == 1){
        $data["serviceLayer"] = true;
        $data["repositoryLayer"] = true;
        $data["mapperLayer"] = true;
        $data["dtoLayer"] = true;
    }

    elseif ($i == 2){
        $data["serviceLayer"] = false;
        $data["repositoryLayer"] = false;
        $data["mapperLayer"] = true;
        $data["dtoLayer"] = true;
    }

    elseif ($i == 3){
        $data["serviceLayer"] = false;
        $data["repositoryLayer"] = false;
        $data["mapperLayer"] = false;
        $data["dtoLayer"] = true;
    }

    elseif ($i == 4){
        $data["serviceLayer"] = false;
        $data["repositoryLayer"] = false;
        $data["mapperLayer"] = false;
        $data["dtoLayer"] = false;
    }

    elseif ($i == 5){
        $data["serviceLayer"] = true;
        $data["repositoryLayer"] = false;
        $data["mapperLayer"] = false;
        $data["dtoLayer"] = false;
    }

    else{
        $data["serviceLayer"] = true;
        $data["repositoryLayer"] = false;
        $data["mapperLayer"] = true;
        $data["dtoLayer"] = true;
    }

    $generatorService->generate(json_decode(json_encode($data)));
}

