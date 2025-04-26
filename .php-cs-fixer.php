<?php

declare(strict_types=1);

require __DIR__.'/vendor/autoload.php';

$app = require __DIR__.'/bootstrap/app.php';

return (new PhpCsFixer\Config())
    ->setUsingCache(false)
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->in($app->path())
            ->in($app->configPath())
            ->in($app->databasePath('factories'))
            ->in($app->databasePath('seeders'))
            ->in($app->basePath('routes'))
            ->in($app->basePath('tests'))
    )
    ->setRules([
        '@PSR12' => true,

        // @see https://cs.symfony.com/doc/rules/#alias
        'array_push' => true,
        'backtick_to_shell_exec' => true,
        'ereg_to_preg' => true,
        'mb_str_functions' => true,
        'modernize_strpos' => true,
        'no_alias_functions' => [
            'sets' => ['@all'],
        ],
        'no_mixed_echo_print' => true,
        'pow_to_exponentiation' => true,
        'random_api_migration' => true,
        'set_type_to_cast' => true,

        // @see https://cs.symfony.com/doc/rules/#array-notation
        'array_syntax' => [
            'syntax' => 'short',
        ],
        'no_multiline_whitespace_around_double_arrow' => true,
        'no_whitespace_before_comma_in_array' => [
            'after_heredoc' => true,
        ],
        'normalize_index_brace' => true,
        'trim_array_spaces' => true,
        'whitespace_after_comma_in_array' => [
            'ensure_single_space' => true,
        ],

        // @see https://cs.symfony.com/doc/rules/#basic
        'curly_braces_position' => [
            'control_structures_opening_brace' => 'same_line',
            'functions_opening_brace' => 'next_line_unless_newline_at_signature_end',
            'anonymous_functions_opening_brace' => 'same_line',
            'classes_opening_brace' => 'next_line_unless_newline_at_signature_end',
            'anonymous_classes_opening_brace' => 'next_line_unless_newline_at_signature_end',
            'allow_single_line_empty_anonymous_classes' => true,
            'allow_single_line_anonymous_functions' => true,
        ],
        'encoding' => true,
        'no_multiple_statements_per_line' => true,
        'no_trailing_comma_in_singleline' => [
            'elements' => [
                'arguments',
                'array_destructuring',
                'array',
                'group_import',
            ],
        ],
        'octal_notation' => true,
        'psr_autoloading' => true,
        'single_line_empty_body' => false,

        // @see https://cs.symfony.com/doc/rules/#casing
        'class_reference_name_casing' => true,
        'constant_case' => [
            'case' => 'lower',
        ],
        'integer_literal_case' => true,
        'lowercase_keywords' => true,
        'lowercase_static_reference' => true,
        'magic_constant_casing' => true,
        'magic_method_casing' => true,
        'native_function_casing' => true,
        'native_function_type_declaration_casing' => true,

        // @see https://cs.symfony.com/doc/rules/#cast-notation
        'cast_spaces' => [
            'space' => 'single',
        ],
        'lowercase_cast' => true,
        'modernize_types_casting' => true,
        'no_short_bool_cast' => true,
        'no_unset_cast' => true,
        'short_scalar_cast' => true,

        // @see https://cs.symfony.com/doc/rules/#class-notation
        'class_attributes_separation' => [
            'elements' => [
                'method' => 'one',
                'property' => 'one',
                'trait_import' => 'none',
                'case' => 'none',
            ],
        ],
        'class_definition' => [
            'multi_line_extends_each_single_line' => true,
            'single_item_single_line' => true,
            'space_before_parenthesis' => true,
            'inline_constructor_arguments' => false,
        ],
        'final_class' => false,
        'final_internal_class' => false,
        'final_public_method_for_abstract_class' => false,
        'no_blank_lines_after_class_opening' => true,
        'no_null_property_initialization' => true,
        'no_php4_constructor' => true,
        'no_unneeded_final_method' => true,
        'ordered_class_elements' => [
            'order' => [
                'use_trait',
                'case',
                'constant_public',
                'constant_protected',
                'constant_private',
                'property_public',
                'property_protected',
                'property_private',
                'construct',
                'destruct',
                'magic',
                'phpunit',
                'method_public',
                'method_protected',
                'method_private',
            ],
            'sort_algorithm' => 'none',
            'case_sensitive' => false,
        ],
        'ordered_interfaces' => [
            'order' => 'alpha',
            'direction' => 'ascend',
        ],
        'ordered_traits' => false,
        'ordered_types' => [
            'sort_algorithm' => 'alpha',
            'null_adjustment' => 'always_last',
        ],
        'protected_to_private' => false,
        'self_accessor' => false,
        'self_static_accessor' => false,
        'single_class_element_per_statement' => [
            'elements' => [
                'const',
                'property',
            ],
        ],
        'single_trait_insert_per_statement' => true,
        'visibility_required' => [
            'elements' =>  [
                'const',
                'property',
                'method',
            ],
        ],

        // @see https://cs.symfony.com/doc/rules/#class-usage
        'date_time_immutable' => false,

        // @see https://cs.symfony.com/doc/rules/#comment
        'comment_to_phpdoc' => false,
        'header_comment' => [
            'header' => '',
        ],
        'multiline_comment_opening_closing' => true,
        'no_empty_comment' => false,
        'no_trailing_whitespace_in_comment' => true,
        'single_line_comment_spacing' => true,
        'single_line_comment_style' => [
            'comment_types' => ['hash'],
        ],

        // @see https://cs.symfony.com/doc/rules/#constant-notation
        'native_constant_invocation' => [
            'fix_built_in' => true,
            'include' => [],
            'exclude' => [],
            'scope' => 'namespaced',
            'strict' => true,
        ],

        // @see https://cs.symfony.com/doc/rules/#control-structure
        'control_structure_braces' => true,
        'control_structure_continuation_position' => [
            'position' => 'same_line',
        ],
        'elseif' => true,
        'empty_loop_body' => [
            'style' => 'semicolon',
        ],
        'empty_loop_condition' => [
            'style' => 'while',
        ],
        'include' => true,
        'no_alternative_syntax' => [
            'fix_non_monolithic_code' => true,
        ],
        'no_break_comment' => [
            'comment_text' => 'no break',
        ],
        'no_superfluous_elseif' => true,
        'no_unneeded_control_parentheses' => [
            'statements' => [
                'break',
                'clone',
                'continue',
                'echo_print',
                'others',
                'return',
                'switch_case',
                'yield',
                'yield_from',
            ],
        ],
        'no_unneeded_curly_braces' => [
            'namespaces' => true,
        ],
        'no_useless_else' => true,
        'simplified_if_return' => true,
        'switch_case_semicolon_to_colon' => true,
        'switch_case_space' => true,
        'switch_continue_to_break' => true,
        'trailing_comma_in_multiline' => [
            'after_heredoc' => false,
            'elements' => [
                'arrays',
                'arguments',
                'match',
                'parameters',
            ],
        ],
        'yoda_style' => [
            'equal' => true,
            'identical' => true,
            'less_and_greater' => null,
            'always_move_variable' => true,
        ],

        // @see https://cs.symfony.com/doc/rules/#function-notation
        'combine_nested_dirname' => true,
        'date_time_create_from_format_call' => true,
        'fopen_flag_order' => true,
        'fopen_flags' => [
            'b_mode' => true,
        ],
        'function_declaration' => [
            'closure_function_spacing' => 'one',
            'closure_fn_spacing' => 'one',
            'trailing_comma_single_line' => false,
        ],
        'implode_call' => true,
        'lambda_not_used_import' => true,
        'method_argument_space' => [
            'keep_multiple_spaces_after_comma' => false,
            'on_multiline' => 'ensure_fully_multiline',
            'after_heredoc' => true,
        ],
        'native_function_invocation' => [
            'exclude' => [],
            'include' => ['@compiler_optimized'],
            'scope' => 'all',
            'strict' => true,
        ],
        'no_spaces_after_function_name' => true,
        'no_unreachable_default_argument_value' => false,
        'no_useless_sprintf' => true,
        'nullable_type_declaration_for_default_null_value' => [
            'use_nullable_type_declaration' => true,
        ],
        'phpdoc_to_param_type' => false,
        'phpdoc_to_property_type' => false,
        'phpdoc_to_return_type' => false,
        'regular_callable_call' => false,
        'return_type_declaration' => [
            'space_before' => 'none',
        ],
        'single_line_throw' => false,
        'static_lambda' => true,
        'use_arrow_functions' => true,
        'void_return' => false,

        // @see https://cs.symfony.com/doc/rules/#import
        'fully_qualified_strict_types' => true,
        'global_namespace_import' => [
            'import_constants' => true,
            'import_functions' => true,
            'import_classes' => null,
        ],
        'group_import' => false,
        'no_leading_import_slash' => true,
        'no_unneeded_import_alias' => true,
        'no_unused_imports' => true,
        'ordered_imports' => [
            'sort_algorithm' => 'alpha',
            'imports_order' => [
                'class',
                'function',
                'const',
            ],
        ],
        'single_import_per_statement' => true,
        'single_line_after_imports' => true,

        // @see https://cs.symfony.com/doc/rules/#language-construct
        'combine_consecutive_issets' => true,
        'combine_consecutive_unsets' => true,
        'declare_equal_normalize' => [
            'space' => 'none',
        ],
        'declare_parentheses' => true,
        'dir_constant' => true,
        'error_suppression' => false,
        'explicit_indirect_variable' => true,
        'function_to_constant' => [
            'functions' => [
                'get_called_class',
                'get_class',
                'get_class_this',
                'php_sapi_name',
                'phpversion',
                'pi',
            ],
        ],
        'get_class_to_class_keyword' => true,
        'is_null' => true,
        'no_unset_on_property' => true,
        'nullable_type_declaration' => [
            'syntax' => 'question_mark',
        ],
        'single_space_around_construct' => true,

        // @see https://cs.symfony.com/doc/rules/#list-notation
        'list_syntax' => [
            'syntax' => 'short',
        ],

        // @see https://cs.symfony.com/doc/rules/#namespace-notation
        'blank_line_after_namespace' => true,
        'blank_lines_before_namespace' => [
            'min_line_breaks' => 2,
            'max_line_breaks' => 2,
        ],
        'clean_namespace' => true,
        'no_leading_namespace_whitespace' => true,

        // @see https://cs.symfony.com/doc/rules/#naming
        'no_homoglyph_names' => true,

        // @see https://cs.symfony.com/doc/rules/#operator
        'assign_null_coalescing_to_coalesce_equal' => true,
        'binary_operator_spaces' => true,
        'concat_space' => [
            'spacing' => 'none',
        ],
        'increment_style' => false,
        'logical_operators' => true,
        'new_with_braces' => true,
        'no_space_around_double_colon' => true,
        'no_useless_concat_operator' => true,
        'no_useless_nullsafe_operator' => true,
        'not_operator_with_space' => false,
        'not_operator_with_successor_space' => false,
        'object_operator_without_whitespace' => true,
        'operator_linebreak' => [
            'only_booleans' => true,
            'position' => 'end',
        ],
        'standardize_increment' => true,
        'standardize_not_equals' => true,
        'ternary_operator_spaces' => true,
        'ternary_to_elvis_operator' => true,
        'ternary_to_null_coalescing' => true,
        'unary_operator_spaces' => true,

        // @see https://cs.symfony.com/doc/rules/#php-tag
        'blank_line_after_opening_tag' => true,
        'echo_tag_syntax' => [
            'format' => 'long',
            'long_function' => 'echo',
            'shorten_simple_statements_only' => false,
        ],
        'full_opening_tag' => true,
        'linebreak_after_opening_tag' => true,
        'no_closing_tag' => true,

        // @see https://cs.symfony.com/doc/rules/#phpdoc
        'align_multiline_comment' => [
            'comment_type' => 'phpdocs_like',
        ],
        'general_phpdoc_annotation_remove' => [
            'annotations' => [
                'class',
                'package',
                'subpackage',
            ],
            'case_sensitive' => false,
        ],
        'no_blank_lines_after_phpdoc' => false,
        'no_empty_phpdoc' => true,
        'no_superfluous_phpdoc_tags' => [
            'allow_mixed' => true,
            'remove_inheritdoc' => true,
            'allow_unused_params' => false,
        ],
        'phpdoc_add_missing_param_annotation' => true,
        'phpdoc_align' => false,
        'phpdoc_annotation_without_dot' => true,
        'phpdoc_indent' => true,
        'phpdoc_inline_tag_normalizer' => true,
        'phpdoc_line_span' => true,
        'phpdoc_no_access' => true,
        'phpdoc_no_alias_tag' => true,
        'phpdoc_no_empty_return' => false,
        'phpdoc_no_package' => true,
        'phpdoc_no_useless_inheritdoc' => true,
        'phpdoc_order_by_value' => true,
        'phpdoc_order' => [
            'order' => [
                'param',
                'return',
                'throws',
            ],
        ],
        'phpdoc_param_order' => true,
        'phpdoc_return_self_reference' => true,
        'phpdoc_scalar' => true,
        'phpdoc_separation' => true,
        'phpdoc_single_line_var_spacing' => true,
        'phpdoc_summary' => true,
        'phpdoc_tag_casing' => true,
        'phpdoc_tag_type' => true,
        'phpdoc_to_comment' => false,
        'phpdoc_trim_consecutive_blank_line_separation' => true,
        'phpdoc_trim' => true,
        'phpdoc_types' => true,
        'phpdoc_types_order' => [
            'sort_algorithm' => 'alpha',
            'null_adjustment' => 'always_last',
        ],
        'phpdoc_var_annotation_correct_order' => true,
        'phpdoc_var_without_name' => true,

        // @see https://cs.symfony.com/doc/rules/#return-notation
        'no_useless_return' => true,
        'return_assignment' => true,
        'simplified_null_return' => false,

        // @see https://cs.symfony.com/doc/rules/#semicolon
        'multiline_whitespace_before_semicolons' => true,
        'no_empty_statement' => true,
        'no_singleline_whitespace_before_semicolons' => true,
        'semicolon_after_instruction' => true,
        'space_after_semicolon' => true,

        // @see https://cs.symfony.com/doc/rules/#strict
        'declare_strict_types' => true,
        'strict_comparison' => true,
        'strict_param' => true,

        // @see https://cs.symfony.com/doc/rules/#string-notation
        'escape_implicit_backslashes' => false,
        'explicit_string_variable' => true,
        'heredoc_to_nowdoc' => true,
        'no_binary_string' => true,
        'no_trailing_whitespace_in_string' => true,
        'simple_to_complex_string_variable' => true,
        'single_quote' => true,
        'string_length_to_empty' => true,
        'string_line_ending' => true,

        // @see https://cs.symfony.com/doc/rules/#whitespace
        'array_indentation' => true,
        'blank_line_before_statement' => [
            'statements' => [
                'break',
                'continue',
                'declare',
                'default',
                'do',
                'exit',
                'for',
                'foreach',
                'goto',
                'if',
                'include',
                'include_once',
                'phpdoc',
                'require',
                'require_once',
                'return',
                'switch',
                'throw',
                'try',
                'while',
                'yield',
                'yield_from',
            ],
        ],
        'blank_line_between_import_groups' => true,
        'compact_nullable_typehint' => true,
        'heredoc_indentation' => true,
        'indentation_type' => true,
        'line_ending' => true,
        'method_chaining_indentation' => false,
        'no_extra_blank_lines' => [
            'tokens' => [
                'attribute',
                'break',
                'case',
                'continue',
                'curly_brace_block',
                'default',
                'extra',
                'parenthesis_brace_block',
                'return',
                'square_brace_block',
                'switch',
                'throw',
                'use',
                'use_trait',
            ],
        ],
        'no_spaces_around_offset' => true,
        'no_spaces_inside_parenthesis' => true,
        'no_trailing_whitespace' => true,
        'no_whitespace_in_blank_line' => true,
        'single_blank_line_at_eof' => true,
        'statement_indentation' => true,
        'type_declaration_spaces' => true,
        'types_spaces' => [
            'space' => 'none',
            'space_multiple_catch' => 'single',
        ],
    ]);
