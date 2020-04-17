<?php


namespace App\Tests\Command;


use PHPUnit\Framework\TestCase;
use App\Command\BPICommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class BPICommandTest extends TestCase
{
    private ?CommandTester $commandTester;

    protected function setUp()
    {
        $application = new Application();
        $application->add(new BPICommand());
        $command = $application->find('FSM');
        $this->commandTester = new CommandTester($command);
    }

    protected function tearDown()
    {
        $this->commandTester = null;
    }

    public function testStateTransition1()
    {
        $this->commandTester->execute(['input' => '1']);
        $output = $this->commandTester->getDisplay();
        $this->assertStringContainsString('Output: 1', $output);
    }

    public function testStateTransition2()
    {
        $this->commandTester->execute(['input' => '10']);
        $output = $this->commandTester->getDisplay();
        $this->assertStringContainsString('Output: 2', $output);
    }

    public function testStateTransitionException()
    {
        $this->expectExceptionMessage("Invalid input 3 for state S1");
        $this->commandTester->execute(['input' => '1113']);
    }

    public function testStateTransitionNoInput()
    {
        $this->commandTester->execute(['input' => '']);
        $output = $this->commandTester->getDisplay();
        $this->assertStringContainsString('Output: 0', $output);
    }
}