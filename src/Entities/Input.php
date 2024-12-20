<?php

declare(strict_types=1);

namespace JuanchoSL\Terminal\Entities;

use JuanchoSL\Terminal\Contracts\InputInterface;

class Input implements InputInterface, \JsonSerializable
{

    /**
     * Summary of arguments
     * @var array<string, mixed>
     */
    protected array $arguments = [];

    public function addArgument(string $name, mixed $value): void
    {
        $this->arguments[$name] = $value;
    }

    public function getArgument(string $name): mixed
    {
        return $this->arguments[$name];
    }

    public function hasArgument(string $name): bool
    {
        return array_key_exists($name, $this->arguments);
    }

    public function jsonSerialize(): array
    {
        return $this->arguments;
    }
}
