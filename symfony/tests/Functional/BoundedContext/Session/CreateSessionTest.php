<?php

declare(strict_types=1);

namespace App\Tests\Functional\BoundedContext\Experience;

use App\BoundedContext\Experience\Domain\Entity\Experience;
use App\BoundedContext\Experience\Domain\Repository\ExperienceRepositoryInterface;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Uuid;

final class CreateSessionTest extends WebTestCase
{
    private Experience $experience;
    private ExperienceRepositoryInterface $experienceRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $client = static::createClient();

        $this->experienceRepository = static::getContainer()->get(ExperienceRepositoryInterface::class);

        $this->experience = new Experience('Experience_Test', 'Description', Uuid::v4());
        $this->experienceRepository->save($this->experience);
    }

    public function testCreateSessionEndpoint(): void
    {
        $client = static::getClient();
        $tomorrow = new DateTime()->modify('+ 1 day');

        $payload = [
            'date' => $tomorrow->format('Y-m-d'),
            'maxCapacity' => 30,
            'price' => 19.99
        ];

        $client->request(
            'POST',
            sprintf('/api/experiences/%s/sessions', $this->experience->getId()->toRfc4122()),
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            (string) json_encode($payload)
        );

        $response = $client->getResponse();

        $this->assertSame(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertStringContainsString('experienceId', (string) $response->getContent());
    }
}
