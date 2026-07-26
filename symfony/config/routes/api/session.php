<?php

declare(strict_types=1);

use App\BoundedContext\Booking\Infrastructure\UI\Controller\CreateBookingController;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return function (RoutingConfigurator $routes) {

    $routes->add(
        name: 'booking_session_experience_creation',
        path: '/sessions/{sessionId}/bookings'
    )
        ->methods(['POST'])
        ->controller(CreateBookingController::class);
};
