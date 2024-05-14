<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\ClassMethod\AddVoidReturnTypeWhereNoReturnRector;
use Rector\Symfony\Set\SymfonySetList;
return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/config',
        __DIR__ . '/public',
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
    ->withSets([
         SymfonySetList::SYMFONY_64, // https://github.com/rectorphp/rector-symfony/tree/main/config/sets/symfony
         SymfonySetList::SYMFONY_CODE_QUALITY,
         SymfonySetList::SYMFONY_CONSTRUCTOR_INJECTION,
         ])
    // uncomment to reach your current PHP version
    // ->withPhpSets()
    ->withRules([
        AddVoidReturnTypeWhereNoReturnRector::class,
    ]);
