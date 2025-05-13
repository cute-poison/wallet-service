<?php

return [
    'default' => 'default',

    'documentations' => [
        'default' => [
            'api' => [
                'title' => 'wallet-service API Documentation',
            ],

            'routes' => [
                /*
                 * Route for accessing API documentation interface
                 */
                'api' => 'api/documentation',
            ],

            'paths' => [
                /*
                 * Include full URL in UI for assets
                 */
                'use_absolute_path'      => env('L5_SWAGGER_USE_ABSOLUTE_PATH', true),

                /*
                 * Path where swagger UI assets should be stored
                 */
                'swagger_ui_assets_path' => env(
                    'L5_SWAGGER_UI_ASSETS_PATH',
                    'vendor/swagger-api/swagger-ui/dist/'
                ),

                /*
                 * File name of the generated JSON documentation file
                 */
                'docs_json'              => 'api-docs.json',

                /*
                 * File name of the generated YAML documentation file
                 */
                'docs_yaml'              => 'api-docs.yaml',

                /*
                 * Which format to use in the UI: json or yaml
                 */
                'format_to_use_for_docs' => env(
                    'L5_FORMAT_TO_USE_FOR_DOCS',
                    'json'
                ),

                /*
                 * Absolute paths to directories containing the Swagger annotations
                 */
                'annotations'            => [
                    base_path('app'),
                ],
            ],
        ],
    ],

    'defaults' => [
        'routes' => [
            /*
             * Route for accessing parsed swagger annotations.
             */
            'docs'            => 'docs',

            /*
             * Route for OAuth2 authentication callback.
             */
            'oauth2_callback' => 'api/oauth2-callback',

            /*
             * Middleware to protect the documentation routes
             */
            'middleware'      => [
                'api'             => [],
                'asset'           => [],
                'docs'            => [],
                'oauth2_callback' => [],
            ],

            /*
             * Any group options for these routes
             */
            'group_options'   => [],
        ],

        'paths' => [
            /*
             * Absolute path where the parsed annotations will be stored
             */
            'docs'    => storage_path('api-docs'),

            /*
             * Absolute path to directory where Swagger UI views are exported
             */
            'views'   => base_path('resources/views/vendor/l5-swagger'),

            /*
             * Base path for your API (if you need to override)
             */
            'base'    => env('L5_SWAGGER_BASE_PATH', null),

            /*
             * Excluded directories (deprecated in favor of scanOptions.exclude)
             */
            'excludes'=> [],
        ],

        'scanOptions' => [
            /*
             * Default swagger-php processor configurations
             */
            'default_processors_configuration' => [],

            /*
             * Custom path processors
             */
            'processors'          => [
                // new \App\SwaggerProcessors\SchemaQueryParameter(),
            ],

            /*
             * File pattern(s) to scan
             */
            'pattern'             => null,

            /*
             * Exclude directories (overrides paths.excludes)
             */
            'exclude'             => [],

            /*
             * OpenAPI spec version: 3.0.0 or 3.1.0
             */
            'open_api_spec_version' => env(
                'L5_SWAGGER_OPEN_API_SPEC_VERSION',
                \L5Swagger\Generator::OPEN_API_DEFAULT_SPEC_VERSION
            ),
        ],

        /*
         * Security schemes for the API documentation
         */
        'securityDefinitions' => [
            'securitySchemes' => [
                'sanctum' => [
                    'type'        => 'apiKey',
                    'description' => 'Enter token in format: Bearer {token}',
                    'name'        => 'Authorization',
                    'in'          => 'header',
                ],
            ],
            'security' => [
                [
                    'sanctum' => []
                ],
            ],
        ],

        /*
         * Always regenerate docs on each request in dev
         */
        'generate_always'    => env('L5_SWAGGER_GENERATE_ALWAYS', true),

        /*
         * Also output a YAML copy if desired
         */
        'generate_yaml_copy' => env('L5_SWAGGER_GENERATE_YAML_COPY', false),

        /*
         * Trust proxy IPs (e.g., AWS ELB)
         */
        'proxy'              => false,

        /*
         * Additional Swagger UI configurations
         */
        'additional_config_url' => null,
        'operations_sort'       => env('L5_SWAGGER_OPERATIONS_SORT', null),
        'validator_url'         => null,

        'ui' => [
            'display' => [
                'dark_mode'     => env('L5_SWAGGER_UI_DARK_MODE', false),
                'doc_expansion' => env('L5_SWAGGER_UI_DOC_EXPANSION', 'none'),
                'filter'        => env('L5_SWAGGER_UI_FILTERS', true),
            ],

            'authorization' => [
                'persist_authorization'       => env(
                    'L5_SWAGGER_UI_PERSIST_AUTHORIZATION',
                    false
                ),
                'use_pkce_with_authorization_code_grant' => false,
            ],
        ],

        /*
         * Useful constants for your annotations
         */
        'constants' => [
            'L5_SWAGGER_CONST_HOST' => env(
                'L5_SWAGGER_CONST_HOST',
                'http://localhost:8000'
            ),
        ],
    ],
];
