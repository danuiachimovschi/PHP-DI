<?php

declare(strict_types=1);

namespace Tests\Examples;

readonly class UserExample
{
    public function __construct(
        private string $name,
    ){}

    public function getName(): string
    {
        return $this->name;
    }
}