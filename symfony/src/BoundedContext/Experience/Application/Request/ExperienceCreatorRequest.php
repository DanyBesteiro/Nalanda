<?php

declare(strict_types=1);

namespace App\BoundedContext\Experience\Application\Request;

use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

class ExperienceCreatorRequest
{
    public function __construct(
        #[Assert\NotBlank]
        public string $title,

        #[Assert\NotNull]
        public string $description,

        #[Assert\NotNull]
        public Uuid $providerId
    ) {}
}
