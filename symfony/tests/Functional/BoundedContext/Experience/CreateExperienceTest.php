<?php

declare(strict_types=1);

namespace App\Tests\Functional\BoundedContext\Experience;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

final class CreateExperienceTest extends WebTestCase
{
    private const PROVIDER_UUID = '44444444-4444-4444-8444-444444444444';

    public function testCreateExperienceEndpoint(): void
    {
        $client = static::createClient();

        $payload = [
            'title' => 'Experience 1',
            'description' => 'An amazing experience',
            'providerId' => self::PROVIDER_UUID
        ];

        $client->request(
            'POST',
            '/api/experiences',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            (string) json_encode($payload)
        );

        $response = $client->getResponse();

        $this->assertSame(Response::HTTP_CREATED, $response->getStatusCode());

        $responseData = json_decode((string) $response->getContent(), true);

        $this->assertIsString($responseData['id']);
        $this->assertSame($payload['title'], $responseData['title']);
        $this->assertSame($payload['description'], $responseData['description']);
        $this->assertSame($payload['providerId'], $responseData['providerId']);
    }

    public function testShouldReturn400WhenUuidIsCorrupt(): void
    {
        $client = static::createClient();

        $payload = [
            'title' => 'Experience 2',
            'description' => 'fake experience',
            'providerId' => 'Not-valid-uuid'
        ];

        $client->request(
            'POST',
            '/api/experiences',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            (string) json_encode($payload)
        );

        $response = $client->getResponse();

        $this->assertSame(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    public function testShouldReturn400WhenTitleIsEmpty(): void
    {
        $client = static::createClient();

        $payload = [
            'title' => '',
            'description' => 'fake experience',
            'providerId' => 'Not-valid-uuid'
        ];

        $client->request(
            'POST',
            '/api/experiences',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            (string) json_encode($payload)
        );

        $response = $client->getResponse();

        $this->assertSame(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }
}
