<?php

$finder = PhpCsFixer\Finder::create()->in(__DIR__);

$config = new PhpCsFixer\Config();

return $config->setRules([
    '@PSR12' => true,
    'binary_operator_spaces' => ['default' => 'single_space'],
    'array_syntax' => ['syntax' => 'short'],
    'blank_line_before_statement' => ['statements' => ['break', 'continue', 'declare', 'return', 'throw', 'try', 'exit']],
    'cast_spaces' => ['space' => 'single'],
    'trailing_comma_in_multiline' => ['elements'=>['arrays']],
    'backtick_to_shell_exec' => true,
    'no_mixed_echo_print' => ['use' => 'echo'],
    'magic_constant_casing' => true,
    'magic_method_casing' => true,
    'native_function_casing' => true,
    'native_function_type_declaration_casing' => true,
    'no_short_bool_cast' => true,
    'no_unset_cast' => true,
    'class_attributes_separation' => ['elements' => ['method'=>'one']],
    'multiline_comment_opening_closing' => true,
    'single_line_comment_style' => ['comment_types' => ['asterisk', 'hash']],
    'no_empty_comment' => true,
    'yoda_style' => true,
    'function_typehint_space' => true,
    'no_unused_imports' => true,
    'combine_consecutive_issets' => true,
    'combine_consecutive_unsets' => true,
    'ternary_to_null_coalescing' => true,
    'echo_tag_syntax'=> ['format'=>'long'],
    'no_useless_return' => true,
    'no_multiline_whitespace_around_double_arrow' => true,
    'normalize_index_brace' => true,
    'array_indentation' => true,
])->setFinder($finder);