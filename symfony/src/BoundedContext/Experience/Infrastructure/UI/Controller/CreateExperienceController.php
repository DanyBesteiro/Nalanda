<?php

declare(strict_types=1);

namespace App\BoundedContext\Experience\Infrastructure\UI\Controller;

use App\BoundedContext\Experience\Application\DTO\ExperienceDTO;
use App\BoundedContext\Experience\Application\Request\ExperienceCreatorRequest;
use App\BoundedContext\Experience\Application\UseCase\ExperienceCreator;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreateExperienceController extends AbstractController
{
    public function __construct(
        private readonly ExperienceCreator $experienceCreator,
        private readonly ValidatorInterface $validator
    ) {}

    public function __invoke(Request $request, SerializerInterface $serializer): JsonResponse
    {
        try {
            $requestDTO = $serializer->deserialize(
                $request->getContent(),
                ExperienceCreatorRequest::class,
                'json'
            );

            $errors = $this->validator->validate($requestDTO);

            if (count($errors) > 0) {
                return new JsonResponse([
                    'error' => 'Validation failed',
                    'details' => (string) $errors
                ], Response::HTTP_BAD_REQUEST);
            }

            $experience = $this->experienceCreator->execute($requestDTO);
            return $this->json(ExperienceDTO::fromEntity($experience), Response::HTTP_CREATED);
        } catch (Exception $e) {
            return new JsonResponse([
                'error' => 'Creation failed',
                'details' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
