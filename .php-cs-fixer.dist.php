<?php

$finder = Symfony\Component\Finder\Finder::create()
    ->exclude('vendor')
    ->name('*.php')
    ->notName('*.blade.php')
    ->in([
        __DIR__.'/src',
        __DIR__.'/tests',
    ])
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

$dive = [
    'binary_operator_spaces' => [
        'operators' => ['|' => null],
    ],
    'blank_line_before_statement' => [
        'statements' => [
            'continue',
            'declare',
            'return',
            'throw',
            'try',
        ],
    ],
    'braces' => false,
    'constant_case' => ['case' => 'lower'],
    'increment_style' => ['style' => 'post'],
    'is_null' => false,
    'method_argument_space' => [
        'on_multiline' => 'ensure_fully_multiline',
        'keep_multiple_spaces_after_comma' => true,
    ],
    'native_constant_invocation' => false,
    'native_function_invocation' => false,
    'not_operator_with_successor_space' => true,
    'no_useless_else' => true,
    'ordered_imports' => [
        'imports_order' => ['class', 'function', 'const'],
        'sort_algorithm' => 'alpha',
    ],
    'phpdoc_to_comment' => false,
    'single_line_throw' => false,
    'trailing_comma_in_multiline' => true,
    'yoda_style' => false,
];

return (new PhpCsFixer\Config())
    ->setRules(array_merge(['@Symfony' => true, '@Symfony:risky' => true], $dive))
    ->setFinder($finder);
