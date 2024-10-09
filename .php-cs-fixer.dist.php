<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__ . '/src');

$config = new PhpCsFixer\Config();
return $config->setRules([
    //Symfony
    '@Symfony' => true,
    '@Symfony:risky' => true,

    //PHP Version Migration
    '@PHP74Migration' => true,
    '@PHP74Migration:risky' => true,
    '@PHP80Migration' => true,
    '@PHP80Migration:risky' => true,

    //PHPUnit Version Migration
    '@PHPUnit84Migration:risky' => true,

    //General
    'array_indentation' => true,
    'compact_nullable_typehint' => true,
    'concat_space' => ['spacing' => 'one'],
    'method_chaining_indentation' => true,
    'multiline_whitespace_before_semicolons' => ['strategy' => 'new_line_for_chained_calls'],
    'ordered_imports' => ['imports_order' => ['class', 'function', 'const']],
    'phpdoc_order' => true,
    'visibility_required' => ['elements' => ['property', 'method', 'const']],
    'yoda_style' => false,
    'octal_notation' => false,

    //'php_unit_test_case_static_method_calls' => ['call_type' => 'this'],
    'global_namespace_import' => [
        'import_classes' => true,
        'import_constants' => true,
        'import_functions' => true,
    ],
    'no_superfluous_phpdoc_tags' => [
        'allow_mixed' => true,
        'remove_inheritdoc' => true,
    ],
])->setFinder($finder);
