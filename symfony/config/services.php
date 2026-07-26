<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use App\BoundedContext\Booking\Domain\Event\BookingCreatedEvent;
use App\BoundedContext\Booking\Infrastructure\Listener\SendBookingToQueueListener;

return static function (ContainerConfigurator $configurator) {

    $services = $configurator->services();

    $services->defaults()
        ->autowire(true)
        ->autoconfigure(true);

    $services->load('App\\', '../src/')
        ->exclude('../src/{DependencyInjection,Tools,Kernel.php}');

    $services->set(SendBookingToQueueListener::class)
        ->tag('kernel.event_listener', [
            'event' => BookingCreatedEvent::class,
            'method' => '__invoke'
        ]);
};
