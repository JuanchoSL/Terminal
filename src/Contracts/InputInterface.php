<?php

declare(strict_types=1);

namespace JuanchoSL\Terminal\Contracts;

interface InputInterface
{

    public function addArgument(string $name, mixed $value): void;
    public function getArgument(string $name): mixed;
}