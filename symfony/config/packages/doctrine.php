<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return function (ContainerConfigurator $configurator) {
    $contexts = [
        'Booking',
        'Experience',
        'Session'
    ];

    $mappings = [];

    foreach ($contexts as $context) {
        $entityDir = '%kernel.project_dir%/src/BoundedContext/' . $context . '/Domain';
        $mappings["$context"] = [
            'is_bundle' => false,
            'type' => 'attribute',
            'dir' => $entityDir,
            'prefix' => "App\\BoundedContext\\$context\\Domain",
            'alias' => $context,
        ];
    }

    $configurator->extension('doctrine', [
        'dbal' => [
            'url' => '%env(resolve:DATABASE_URL)%',
            'server_version' => '15',
        ],
        'orm' => [
            'auto_mapping' => true,
            'mappings' => $mappings
        ]
    ]);
};
