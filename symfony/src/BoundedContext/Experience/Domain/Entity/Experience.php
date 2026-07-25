<?php

declare(strict_types=1);

namespace App\BoundedContext\Experience\Domain\Aggregate;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
#[ORM\Table(name: 'experience')]
class Experience
{
    #[ORM\Id]
    #[ORM\Column(type: 'guid')]
    private Uuid $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $title;

    #[ORM\Column(type: 'text')]
    private string $description;

    #[ORM\Column(type: 'guid', name: 'provider_id')]
    private Uuid $providerId;

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getProviderId(): Uuid
    {
        return $this->providerId;
    }

    public function setId(Uuid $id): void
    {
        $this->id = $id;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function setProviderId(Uuid $providerId): void
    {
        $this->providerId = $providerId;
    }
}
