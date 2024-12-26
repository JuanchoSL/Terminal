<?php

declare(strict_types=1);

namespace JuanchoSL\Terminal\Contracts;

interface InputInterface
{
    /**
     * Set a value for a parameter
     * @param string $name The parameter name
     * @param mixed $value The parameter value
     * @return self The same Input object
     */
    public function addArgument(string $name, mixed $value): static;

    /**
     * Retrieve the parameter value indicated with name
     * @param string $name The parameter name
     * @return mixed The parameter value
     */
    public function getArgument(string $name): mixed;

    /**
     * Check if a parameter is setted
     * @param string $name The parameter name to check
     * @return bool True if is setted, false otherwise
     */
    public function hasArgument(string $name): bool;
}