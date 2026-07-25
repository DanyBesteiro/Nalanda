<?php

declare(strict_types=1);

use App\BoundedContext\Experience\Infrastructure\UI\Controller\CreateExperienceController;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return function (RoutingConfigurator $routes) {
    $routes->add(
        name: 'experience_creation',
        path: '/experiences'
    )
        ->methods(['POST'])
        ->controller(CreateExperienceController::class);
};
