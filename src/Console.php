<?php

declare(strict_types=1);

namespace JuanchoSL\Terminal;

use JuanchoSL\Terminal\Contracts\CommandInterface;
use JuanchoSL\Exceptions\DestinationUnreachableException;

class Console
{

    protected array $commands = [];

    public function add(CommandInterface $command)
    {
        $this->commands[$command->getName()] = $command;
    }
    public function run(): void
    {
        if (!array_key_exists($_SERVER['argv'][1], $this->commands)) {
            throw new DestinationUnreachableException(sprintf("The command '%s' is not defined", $_SERVER['argv'][1]));
        }
        $this->commands[$_SERVER['argv'][1]]->run(array_slice($_SERVER['argv'], 1));
    }

}
