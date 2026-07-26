<?php

declare(strict_types=1);

namespace App\BoundedContext\Booking\Infrastructure\UI\Controller;

use App\BoundedContext\Booking\Application\DTO\BookingDTO;
use App\BoundedContext\Booking\Application\Request\BookingCreatorRequest;
use App\BoundedContext\Booking\Application\UseCase\BookingCreator;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreateBookingController extends AbstractController
{
    public function __construct(
        private readonly BookingCreator $bookingCreator,
        private readonly ValidatorInterface $validator
    ) {}

    public function __invoke(string $sessionId, Request $request): JsonResponse
    {
        try {
            /** @var array{userId: string, places: int} $data */
            $data = $request->toArray();

            $bookingRequest = new BookingCreatorRequest(
                sessionId: Uuid::fromString($sessionId),
                userId: Uuid::fromString($data['userId']),
                places: $data['places'],
            );

            $errors = $this->validator->validate($bookingRequest);

            if (count($errors) > 0) {
                return new JsonResponse([
                    'error' => 'Validation failed',
                    'details' => (string) $errors
                ], Response::HTTP_BAD_REQUEST);
            }

            $booking = $this->bookingCreator->execute($bookingRequest);
            return $this->json(BookingDTO::fromEntity($booking), Response::HTTP_CREATED);
        } catch (Exception $e) {
            return new JsonResponse([
                'error' => 'Creation failed',
                'details' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
