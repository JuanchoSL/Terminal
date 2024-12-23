<?php

declare(strict_types=1);

namespace JuanchoSL\Terminal;

use JuanchoSL\Terminal\Contracts\CommandInterface;
use JuanchoSL\Exceptions\DestinationUnreachableException;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;

class Console implements LoggerAwareInterface
{

    use LoggerAwareTrait;

    /**
     * Summary of commands
     * @var array<string, CommandInterface>
     */
    protected array $commands = [];

    protected bool $debug = false;

    public function setDebug(bool $debug = false): static
    {
        $this->debug = $debug;
        return $this;
    }

    public function add(CommandInterface $command): void
    {
        if (isset($this->logger)) {
            $command->setLogger($this->logger);
        }
        $command->setDebug($this->debug);
        $this->commands[$command->getName()] = $command;
    }
    public function run(): int
    {
        if (empty($_SERVER['argv'][1]) || $_SERVER['argv'][1] == 'help') {
            $this->help();
            exit;
        }
        if (!array_key_exists($_SERVER['argv'][1], $this->commands)) {
            throw new DestinationUnreachableException(sprintf("The command '%s' is not defined", $_SERVER['argv'][1]));
        }
        return $this->commands[$_SERVER['argv'][1]]->run(array_slice($_SERVER['argv'], 2));
    }

    protected function help()
    {
        $response = "Available commands:" . PHP_EOL;
        foreach (array_keys($this->commands) as $command) {
            $response .= "- {$command}" . PHP_EOL;
        }
        echo $response;
    }
}
