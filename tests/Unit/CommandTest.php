<?php

namespace JuanchoSL\Terminal\Tests\Unit;

use JuanchoSL\Exceptions\PreconditionRequiredException;
use JuanchoSL\Terminal\Entities\Input;
use JuanchoSL\Terminal\Tests\UseCaseCommand;
use PHPUnit\Framework\TestCase;

class CommandTest extends TestCase
{

    public function testMissingRequired()
    {
        $this->expectException(PreconditionRequiredException::class);
        $app = new UseCaseCommand();
        $app->run(['--required_void', '--required_multi', 'a', 'b', 'c']);
    }

    public function testWithoutArgs()
    {
        $_SERVER['argv'][] = '--required_single=./';
        $_SERVER['argv'][] = '--required_void';
        $_SERVER['argv'][] = '--required_multi';
        $_SERVER['argv'][] = 'a';
        $_SERVER['argv'][] = 'b';
        $_SERVER['argv'][] = 'c';
        $app = new UseCaseCommand();
        $code = $app->run();
        $this->assertEquals(0, $code);
    }

    public function testWithArray()
    {
        $app = new UseCaseCommand();
        $code = $app->run(['--required_single', 'value', '--required_void', '--required_multi', 'a', 'b', 'c']);
        $this->assertEquals(0, $code);
    }

    public function testWithInput()
    {
        $input = new Input;
        $input->addArgument('required_single', 'value');
        $input->addArgument('required_void', null);
        $input->addArgument('required_multi', ['a', 'b', 'c']);
        
        $app = new UseCaseCommand();
        $code = $app->run($input);
        $this->assertEquals(0, $code);
    }
}