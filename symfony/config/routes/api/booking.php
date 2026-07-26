<?php

declare(strict_types=1);

use App\BoundedContext\Booking\Infrastructure\UI\Controller\UpdateBookingController;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return function (RoutingConfigurator $routes) {

    $routes->add(
        name: 'booking_session_experience_update',
        path: '/bookings/{bookingId}'
    )
        ->methods(['PATCH'])
        ->controller(UpdateBookingController::class);
};
