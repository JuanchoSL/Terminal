<?php

declare(strict_types=1);

namespace JuanchoSL\Terminal\Contracts;

use JuanchoSL\Terminal\Enums\InputArgument;
use JuanchoSL\Terminal\Enums\InputOption;

interface CommandInterface
{
    public function getName(): string;
    public function run(array $arguments = []): int;
    public function addArgument(string $name, InputArgument $required, InputOption $option): void;
    public function getArgument(string $name): mixed;
}