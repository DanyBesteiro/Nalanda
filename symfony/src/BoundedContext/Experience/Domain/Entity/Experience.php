<?php

declare(strict_types=1);

namespace App\BoundedContext\Experience\Domain\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
#[ORM\Table(name: 'experience')]
class Experience
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid')]
    private Uuid $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $title;

    #[ORM\Column(type: 'text')]
    private string $description;

    #[ORM\Column(type: 'uuid', name: 'provider_id')]
    private Uuid $providerId;

    public function __construct(
        string $title,
        string $description,
        Uuid $providerId
    ) {
        $this->id = Uuid::v4();

        $this->title = $title;
        $this->description = $description;
        $this->providerId = $providerId;

        if (empty(trim($this->title))) {
            throw new \InvalidArgumentException('Not valid title');
        }
    }

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
}
