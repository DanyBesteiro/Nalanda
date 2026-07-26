<?php

declare(strict_types=1);

namespace App\Tests\Functional\BoundedContext\Experience;

use App\BoundedContext\Experience\Domain\Entity\Experience;
use App\BoundedContext\Experience\Domain\Repository\ExperienceRepositoryInterface;
use App\BoundedContext\Session\Domain\Entity\Session;
use App\BoundedContext\Session\Domain\Repository\SessionRepositoryInterface;
use DateTime;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Uuid;

final class CreateBookingTest extends WebTestCase
{
    private Experience $experience;
    private ExperienceRepositoryInterface $experienceRepository;

    private Session $session;
    private SessionRepositoryInterface $sessionRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $client = static::createClient();

        $this->experienceRepository = static::getContainer()->get(ExperienceRepositoryInterface::class);
        $this->sessionRepository =  static::getContainer()->get(SessionRepositoryInterface::class);

        $this->experience = new Experience('Experience_Test', 'Description', Uuid::v4());
        $this->experienceRepository->save($this->experience);

        $this->session = new Session($this->experience->getId(), new DateTimeImmutable('+10 days'), 15, 3.5);
        $this->sessionRepository->save($this->session);
    }

    public function testCreatingBookingEndpoint(): void
    {
        $client = static::getClient();

        $payload = [
            'userId' => Uuid::v4()->toRfc4122(),
            'places' => 4
        ];

        $client->request(
            'POST',
            sprintf('/api/sessions/%s/bookings', $this->session->getId()),
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            (string) json_encode($payload)
        );

        $response = $client->getResponse();

        $this->assertSame(Response::HTTP_CREATED, $response->getStatusCode());
    }
}
