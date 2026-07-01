<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php81\Rector\FuncCall\NullToStrictStringFuncCallArgRector;
use Rector\Php84\Rector\Param\ExplicitNullableParamTypeRector;
use Rector\PHPUnit\Set\PHPUnitSetList;
use Rector\ValueObject\PhpVersion;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/lib',
        __DIR__ . '/bin',
        __DIR__ . '/test',
    ])
    // The library declares "php": ">=8.0" so consumers on any 8.x keep working.
    // Pin Rector's analysis target to 8.5 so it surfaces the 8.1-8.5 rules below
    // (it would otherwise cap language rules at 8.0 and skip them).
    ->withPhpVersion(PhpVersion::PHP_85)
    // Production code (lib/, bin/) gets ONLY the rules that fix real PHP 8.1-8.5
    // deprecations, keeping the diff against upstream minimal. We deliberately do
    // not enable withPhpSets() here, which would also restyle working code
    // (short arrays, constructor promotion, never types, ...). Enable it later if
    // a full modernization pass is wanted.
    ->withRules([
        // 8.4: "Implicitly marking parameter nullable is deprecated"
        ExplicitNullableParamTypeRector::class,
        // 8.1: passing null to a non-nullable internal string argument is deprecated
        NullToStrictStringFuncCallArgRector::class,
    ])
    // Bring the PHPUnit 3.7-era tests up to the modern (11.x) API and convert
    // remaining metadata annotations to attributes. These rules only match
    // PHPUnit test classes, so they leave lib/ and bin/ untouched.
    ->withSets([
        PHPUnitSetList::PHPUNIT_100,
        PHPUnitSetList::PHPUNIT_110,
        PHPUnitSetList::ANNOTATIONS_TO_ATTRIBUTES,
    ]);
