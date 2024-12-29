<?php

declare(strict_types=1);

namespace JuanchoSL\Terminal\Entities;

use JuanchoSL\Terminal\Contracts\InputInterface;

class Input implements InputInterface
{

    /**
     * Summary of arguments
     * @var array<string, mixed>
     */
    protected array $arguments = [];

    public function addArgument(string $name, mixed $value): static
    {
        $this->arguments[$name] = $value;
        return $this;
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

    public function __tostring(): string
    {
        $response = '';
        foreach ($this->arguments as $name => $value) {
            $value = (is_array($value)) ? implode(' ', $value) : $value;
            if (!empty($response)) {
                $response .= ' ';
            }
            $response .= '--' . $name . ' ' . $value;
        }
        return $response;
    }
}
