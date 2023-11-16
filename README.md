# Gerador de código para Laravel

Incluir dependência em `config/app.php`:

```php
    ... 
    
    'providers' => [
        ...
        
        \JocelimJr\LaravelApiGenerator\LaravelGeneratorServiceProvider::class,
    ],
    
    ...
```

### Comandos

```
make:interface       Criar interface
make:repository      Criar classe Repository com Interface
make:dto             Criar classe DTO
api:create           Criar novo módulo (module.json)
api:generate         Gerar API de acordo com módulos
```

### Erros

```
Importação da rota, no providers
Incluir validações, com base no json
Incluir Tipos Migration criada automatica (https://laravel.com/docs/9.x/migrations#available-column-types)
Inutilizar json ao criar módulo
```
