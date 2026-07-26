<?php

declare(strict_types=1);

namespace App\BoundedContext\Booking\Infrastructure\UI\Controller;

use App\BoundedContext\Booking\Application\UseCase\BookingCancelator;
use App\BoundedContext\Booking\Domain\ValueObject\BookingStatus;
use Exception;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UpdateBookingController extends AbstractController
{
    public function __construct(private readonly BookingCancelator $bookingCancelator) {}

    public function __invoke(string $bookingId, Request $request): JsonResponse
    {
        try {
            /** @var array{status: string} $data */
            $data = $request->toArray();

            if ($data['status'] === BookingStatus::CANCELED->value) {
                $this->bookingCancelator->execute($bookingId);
            }

            return $this->json(['message' => 'Booking updated'], Response::HTTP_OK);
        } catch (Exception $e) {
            return new JsonResponse([
                'error' => 'Update failed',
                'details' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
