<?php

declare(strict_types=1);

namespace App\BoundedContext\Session\Infrastructure\UI\Controller;

use App\BoundedContext\Session\Application\DTO\SessionDTO;
use App\BoundedContext\Session\Application\Request\SessionCreatorRequest;
use App\BoundedContext\Session\Application\UseCase\SessionCreator;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreateSessionController extends AbstractController
{
    public function __construct(
        private readonly SessionCreator $sessionCreator,
        private readonly ValidatorInterface $validator
    ) {}

    public function __invoke(string $experienceId, Request $request,): JsonResponse
    {
        try {
            $data = $request->toArray();

            $sessionRequest = new SessionCreatorRequest(
                experienceId: Uuid::fromString($experienceId),
                date: $data['date'] ?? '',
                maxCapacity: (int) ($data['maxCapacity'] ?? 0),
                price: (float) ($data['price'] ?? 0.0)
            );

            $errors = $this->validator->validate($sessionRequest);
            if (count($errors) > 0) {
                return new JsonResponse([
                    'error' => 'Validation failed',
                    'details' => (string) $errors
                ], Response::HTTP_BAD_REQUEST);
            }

            $experience = $this->sessionCreator->execute($sessionRequest);
            return $this->json(SessionDTO::fromEntity($experience), Response::HTTP_CREATED);
        } catch (Exception $e) {
            return new JsonResponse([
                'error' => 'Creation failed',
                'details' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
