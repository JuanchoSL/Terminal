<?php

declare(strict_types=1);

namespace JuanchoSL\Terminal\Tests;

use JuanchoSL\Terminal\Command;
use JuanchoSL\Terminal\Contracts\InputInterface;
use JuanchoSL\Terminal\Enums\InputArgument;
use JuanchoSL\Terminal\Enums\InputOption;

class UseCaseCommand extends Command
{

    public function getName(): string
    {
        return "usecase";
    }

    protected function configure(): void
    {
        $this->addArgument('required_single', InputArgument::REQUIRED, InputOption::SINGLE);
        $this->addArgument('required_multi', InputArgument::REQUIRED, InputOption::MULTI);
        $this->addArgument('required_void', InputArgument::OPTIONAL, InputOption::VOID);
    }

    protected function execute(InputInterface $input): int
    {
        return 0;
    }
}