<?php

declare(strict_types=1);

namespace JuanchoSL\Terminal\Entities;

use JuanchoSL\Terminal\Contracts\InputInterface;

class Input implements InputInterface
{

    protected array $arguments = [];

    public function addArgument(string $name, mixed $value): void
    {
        $this->arguments[$name] = $value;
    }

    public function getArgument(string $name): mixed
    {
        return $this->arguments[$name];
    }
}
