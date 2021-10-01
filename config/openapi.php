<?php

return [

    'collections' => [

        'default' => [

            'info' => [
                'title' => 'タイトル',
                'description' => '説明',
                'version' => '1.0.0',
                'contact' => [],
            ],

            'servers' => [],

            'tags' => [

                // [
                //    'name' => 'user',
                //    'description' => 'Application users',
                // ],

            ],

            'security' => [
                // GoldSpecDigital\ObjectOrientedOAS\Objects\SecurityRequirement::create()->securityScheme('JWT'),
            ],

            // Route for exposing specification.
            // Leave uri null to disable.
            'route' => [

            ],

            // Register custom middlewares for different objects.
            'middlewares' => [

            ],

        ],

    ],
];
