<?php

namespace JuanchoSL\Terminal\Tests\Funtional;

use JuanchoSL\Exceptions\DestinationUnreachableException;
use JuanchoSL\Exceptions\PreconditionRequiredException;
use JuanchoSL\Terminal\Console;
use JuanchoSL\Terminal\Tests\UseCaseCommand;
use PHPUnit\Framework\TestCase;

class ConsoleTest extends TestCase
{

    protected Console $app;

    protected function setUp(): void
    {
        $app = new Console;
        $app->add(new UseCaseCommand);
        $this->app = $app;
    }

    protected function tearDown(): void
    {
        unset($this->app);
    }
    public function testMissingCommand()
    {
        $this->expectException(DestinationUnreachableException::class);
        $_SERVER['argv'] = [];
        $_SERVER['argv'][] = '--required_void';
        $_SERVER['argv'][] = '--required_multi';
        $_SERVER['argv'][] = 'a';
        $_SERVER['argv'][] = 'b';
        $_SERVER['argv'][] = 'c';
        $this->app->run();
    }
    public function testMissingRequired()
    {
        $this->expectException(PreconditionRequiredException::class);
        $_SERVER['argv'] = [];
        $_SERVER['argv'][] = 'entryppoint';
        $_SERVER['argv'][] = 'usecase';
        $_SERVER['argv'][] = '--required_void';
        $_SERVER['argv'][] = '--required_multi';
        $_SERVER['argv'][] = 'a';
        $_SERVER['argv'][] = 'b';
        $_SERVER['argv'][] = 'c';
        $this->app->run();
    }

    public function testWithEquals()
    {
        $_SERVER['argv'] = [];
        $_SERVER['argv'][] = 'entryppoint';
        $_SERVER['argv'][] = 'usecase';
        $_SERVER['argv'][] = '--required_single=./';
        $_SERVER['argv'][] = '--required_void';
        $_SERVER['argv'][] = '--required_multi';
        $_SERVER['argv'][] = 'a';
        $_SERVER['argv'][] = 'b';
        $_SERVER['argv'][] = 'c';
        $code = $this->app->run();
        $this->assertEquals(0, $code);
    }
    public function testWithParam()
    {
        $_SERVER['argv'] = [];
        $_SERVER['argv'][] = 'entryppoint';
        $_SERVER['argv'][] = 'usecase';
        $_SERVER['argv'][] = '--required_single';
        $_SERVER['argv'][] = './';
        $_SERVER['argv'][] = '--required_void';
        $_SERVER['argv'][] = '--required_multi';
        $_SERVER['argv'][] = 'a';
        $_SERVER['argv'][] = 'b';
        $_SERVER['argv'][] = 'c';
        $code = $this->app->run();
        $this->assertEquals(0, $code);
    }

}