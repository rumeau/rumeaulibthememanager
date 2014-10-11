<?php
return [
    'rumeaulib_thememanager' => [
        'themes_dir' => 'data/themes',

        'cache' => [
            'adapter'   => [
                'name' => 'memory',
            ],
            'plugins'   => [
                'serializer',
            ]
        ],
    ],

    'service_manager' => [
        'factories' => [
            'RumeauLibThemeManager\ThemeManager' => 'RumeauLibThemeManager\Service\ThemeManagerFactory',
        ],
    ],
];