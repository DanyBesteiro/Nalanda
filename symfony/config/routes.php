<?php

declare(strict_types=1);

use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return function (RoutingConfigurator $routes) {

    $routes->import('routes/api/experience.php')
        ->prefix('/api');

    $routes->import('routes/api/session.php')
        ->prefix('/api');
};
