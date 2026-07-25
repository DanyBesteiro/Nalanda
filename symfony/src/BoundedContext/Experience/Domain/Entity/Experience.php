<?php

declare(strict_types=1);

namespace App\BoundedContext\Experience\Domain\Aggregate;

use Symfony\Component\Uid\Uuid;

class Experience
{
    public function __construct(
        private Uuid $id,
        private string $title,
        private string $description,
        private string $providerId
    ) {}

    public function id(): Uuid
    {
        return $this->id;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function description(): string
    {
        return $this->description;
    }

    public function providerId(): string
    {
        return $this->providerId;
    }
}
