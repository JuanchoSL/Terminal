<?php

declare(strict_types=1);

namespace JuanchoSL\Terminal\Contracts;

use JuanchoSL\Terminal\Enums\InputArgument;
use JuanchoSL\Terminal\Enums\InputOption;

interface CommandInterface
{
    /**
     * The command name in order to call from console
     * @return string The command name
     */
    public function getName(): string;

    /**
     * Execute the command
     * @param array $arguments console parameters
     * @return int The execution result code
     */
    public function run(array $arguments = []): int;

    /**
     * Summary of addArgument
     * @param string $name
     * @param \JuanchoSL\Terminal\Enums\InputArgument $required
     * @param \JuanchoSL\Terminal\Enums\InputOption $option
     * @return void
     */
    public function addArgument(string $name, InputArgument $required, InputOption $option): void;

    /**
     * Retrieve the parameter value indicated with name
     * @param string $name The parameter name
     * @return mixed The parameter value
     */
    public function getArgument(string $name): mixed;
}