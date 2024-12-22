<?php

declare(strict_types=1);

namespace JuanchoSL\Terminal;

use JuanchoSL\Exceptions\PreconditionRequiredException;
use JuanchoSL\Terminal\Contracts\CommandInterface;
use JuanchoSL\Terminal\Contracts\InputInterface;
use JuanchoSL\Terminal\Entities\Input;
use JuanchoSL\Terminal\Enums\InputArgument;
use JuanchoSL\Terminal\Enums\InputOption;
use Psr\Log\LoggerAwareTrait;

abstract class Command implements CommandInterface
{

    use LoggerAwareTrait;

    /**
     * Summary of arguments
     * @var array<string, array<string, InputArgument|InputOption>>
     */
    protected array $arguments = [];
    protected bool $debug = false;

    public function setDebug(bool $debug = false): static
    {
        $this->debug = $debug;
        return $this;
    }

    protected function log(\Stringable|string $message, $log_level, $context = []): void
    {
        if (isset($this->logger)) {
            if ($this->debug || $log_level != 'debug') {
                $context['memory'] = memory_get_usage();
                if (!array_key_exists('command', $context)) {
                    $context['command'] = implode(' ', $_SERVER['argv']);
                }
                $this->logger->log($log_level, $message, $context);
            }
        }
    }

    /**
     * Summary of setRequest
     * @param array<int, string> $argv
     * @return \JuanchoSL\Terminal\Entities\Input
     */
    protected function setRequest(string ...$argv): Input
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
                } elseif (array_key_exists($argument, $this->arguments)) {
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

    protected function validate(InputInterface $vars): void
    {
        foreach ($this->arguments as $name => $argument) {
            if ($argument['argument'] == InputArgument::REQUIRED && !$vars->hasArgument($name)) {
                $exception = new PreconditionRequiredException("The argument '{$name}' is missing");
                $this->log($exception, 'error', [
                    'exception' => $exception,
                    'parameters' => $vars,
                    'arguments' => $this->arguments
                ]);
                throw $exception;
            }
        }
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

    public function run(InputInterface|array|null $args = null): int
    {
        $time = time();
        $this->configure();
        if(is_null($args)){
            $args = array_slice($_SERVER['argv'], 1);
        }
        $input = (is_array($args)) ? $this->setRequest(...$args): $args;
        $this->validate($input);
        $result = $this->execute($input);
        $this->log("Command: '{command}'", 'debug', [
            'command' => implode(' ', $_SERVER['argv']),
            'arguments' => $args,
            'input' => $input,
            'result' => $result,
            'time' => time() - $time
        ]);
        return $result;
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
        } elseif (is_scalar($values)) {
            echo $values;
        } else {
            echo print_r($values, true);
        }
        echo PHP_EOL;
    }
    abstract protected function execute(InputInterface $input): int;

    abstract protected function configure(): void;

}
