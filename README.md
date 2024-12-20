# Terminal

## Description

Little methods collection in order to create console commands

## Install

```bash
composer require juanchosl/terminal
composer update
```

## How to use

### Create a Command, extending the Command Library class

Commans is a simple implementation of use cases, we need to define the name, arguments and code to execute

#### Name
The name of the command, first argument to indicate the action to execute
```php
    public function getName(): string
    {
        return "copy";
    }
```

#### Configuration
Declare the arguments, his name, if is required and the type of values, void if don't have, simple or multiple
```php
    protected function configure(): void
    {
        $this->addArgument('origin', InputArgument::REQUIRED, InputOption::SINGLE);
        $this->addArgument('destiny', InputArgument::REQUIRED, InputOption::MULTIPLE);
        $this->addArgument('copies', InputArgument::OPTIONAL, InputOption::SINGLE);
        $this->addArgument('clean', InputArgument::OPTIONAL, InputOption::VOID);
    }
```

#### Execution
The code to execute, as an use case, returning an integer as result code, 0 for no errors
```php
    protected function execute(InputInterface $input): int
    {
        $this->write($input->getArgument('origin'));
        $this->write($input->getArgument('destiny'));
        if ($input->hasArgument('copies')) {
            $this->write($input->getArgument('copies'));
        }
        if ($input->hasArgument('clean')) {
            $this->write($input->getArgument('clean'));
        }
        return 0;
    }
```

### Create an App call

#### Direct command call
We can execute directly the command created previously. If we do not pass parameters to run method, the library read the _SERVER global in order to retrieve the parameters passed to php script used as entrypoint

```php
$command = new CopyCommand();
$command->run();
```

Then, call the script from the console, passing the command name and the parameters desireds
```bash
./entrypoint --origin=values.txt --destiny values.csv values.xml --copies 1 --clean
```

#### Create an App with multiple commands
In our entrypoint script, we can declare some commands in order to use it with multiple purposes, using the name of the desired command as first parameter.
Create a file and put the use case commands

```php
use JuanchoSL\Terminal\Console;
use App\CopyCommand;
use App\DeleteCommand;

$app = new Console;
$app->add(new CopyCommand);
$app->add(new DeleteCommand);
$app->run();
```

Then, call the script from the console, passing the command name and the parameters desireds
```bash
./entrypoint copy --origin=values.txt --destiny values.csv values.xml --copies 1 --clean
```

## Debug
The library implements the LoggerAwaitInterface, in order to indicate a PSR3 Logger for save errors or debug info. The loggers are used by commands, but if you have multiple commands from a Console App, can pass an unique log to Console class before insert Commands and the logger will be inserted into any Command used
```php
use JuanchoSL\Terminal\Console;
use App\CopyCommand;
use App\DeleteCommand;
use JuanchoSL\Logger\Composers\TextComposer;
use JuanchoSL\Logger\Logger;
use JuanchoSL\Logger\Repositories\FileRepository;

$file_logger = new Logger((new FileRepository(realpath(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR . 'errors.log'))->setComposer(new TextComposer));

$app = new Console();
$app->setLogger($file_logger);
$app->setDebug(false);
$app->add(new CopyCommand);
$app->add(new DeleteCommand);
$app->run();
```

If you enable the debug mode, all run actions will be traced into log
 ```txt
[2024-12-20T20:09:54+00:00] [debug] Command: './entrypoint delete --folder=../files/*.old'
{
    "arguments": [
        "delete",
        "--folder=..\/files\/*.old*"
    ],
    "input": {
        "folder": "..\/files\/*.old*"
    },
    "result": 0,
    "time": 0,
    "memory": 1184304
}
 ```