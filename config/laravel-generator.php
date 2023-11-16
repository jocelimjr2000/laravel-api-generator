<?php

return [

    'path' => storage_path('laravel-generator'),

    'default' => [
        'apiName' => null,
        'module' => null,
        'routePrefix' => null,
        'prefixDateMigration' => null,
        'table' => null,
        'columns' => [],
        'primaryKey' => [
            'name' => null,
            'type' => null,
            'alias' => null,
        ],
        'architecture' => [
            'service' => true,
            'repository' => true,
            'mapper' => true,
            'dto' => true,
        ],
        'createFile' => [
            'routes' => true,
            'controller' => true,
            'formRequest' => true,
            'migration' => true,
            'model' => true,
            'repository' => true,
            'mapper' => true,
            'dto' => true,
            'service' => true,
            'featureTest' => true,
        ],
        'fileName' => [
            'routes' => null,
            'controller' => null,
            'formRequest' => null,
            'formCreateRequest' => null,
            'formUpdateRequest' => null,
            'formDeleteRequest' => null,
            'model' => null,
            'repository' => null,
            'repositoryInterface' => null,
            'mapper' => null,
            'dto' => null,
            'service' => null,
            'featureTest' => null,
        ],
        'writeApi' => [
            'findAll' => true,
            'findById' => true,
            'create' => true,
            'update' => true,
            'delete' => true,
        ],
        'swagger' => [
            'generate' => true,
            'tags' => []
        ]
    ],

    'packages' => [
        'modules' => 'nwidart/laravel-modules'
    ]
];
