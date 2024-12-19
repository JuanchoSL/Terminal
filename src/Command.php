<?php

declare(strict_types=1);

namespace JuanchoSL\Terminal;

use JuanchoSL\Terminal\Contracts\CommandInterface;
use JuanchoSL\Terminal\Contracts\InputInterface;
use JuanchoSL\Terminal\Entities\Input;
use JuanchoSL\Terminal\Enums\InputArgument;
use JuanchoSL\Terminal\Enums\InputOption;

abstract class Command implements CommandInterface
{

    protected array $arguments = [];

    protected function setRequest($argv): Input
    {
        $params = [];
        $key = null;
        for ($i = 0; $i < count($argv); $i++) {
            $argument = $argv[$i];
            $value = null;
            if (substr($argument, 0, 2) == '--') {
                $key = null;
                $argument = substr($argument, 2);

                if (strpos($argument, '=') !== false) {
                    list($argument, $value) = explode('=', $argument);
                } else {
                    switch ($this->arguments[$argument]['option']) {
                        case InputOption::VOID:
                            $value = true;
                            break;
                        case InputOption::SINGLE:
                        case InputOption::MULTI:
                            $value = $argv[++$i];
                            $key = $argument;
                            break;
                    }
                }
            } elseif (!is_null($key)) {
                $value = $argument;
                $argument = $key;
            }

            if (array_key_exists($argument, $this->arguments)) {
                switch ($this->arguments[$argument]['option']) {
                    case InputOption::VOID:
                    case InputOption::SINGLE:
                        $params[$argument] = $value;
                        break;
                    case InputOption::MULTI:
                        $params[$argument][] = $value;
                        break;
                }
            }
        }

        $input = new Input;
        foreach ($params as $param => $value) {
            $input->addArgument($param, $value);
        }
        return $input;
    }

    public function addArgument(string $name, InputArgument $required, InputOption $option): void
    {
        $this->arguments[$name] = [
            'argument' => $required,
            'option' => $option
        ];
    }
    public function getArgument(string $name): mixed
    {
        return $this->arguments[$name];
    }

    public function run(?array $args = null): int
    {
        $args ??= array_slice($_SERVER['argv'], 1);
        $this->configure();
        $input = $this->setRequest($args);
        return $this->execute($input);
    }

    protected function write(mixed $values): void
    {
        if (is_array($values)) {
            echo "<pre>" . print_r($values, true);
        } elseif (is_string($values) || $values instanceof \Stringable) {
            echo (string) $values;
        } elseif ($values instanceof \JsonSerializable) {
            echo (string) json_encode($values, JSON_PRETTY_PRINT);
        } elseif (is_object($values)) {
            var_dump($values);
        } else {
            echo $values;
        }
        echo PHP_EOL;
    }
    abstract protected function execute(InputInterface $input): int;

    abstract protected function configure(): void;

}
