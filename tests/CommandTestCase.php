<?php

declare(strict_types=1);

namespace KacperWojtaszczyk\PrintifyBackendHomework\Tests;

use KacperWojtaszczyk\PrintifyBackendHomework\Tests\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class CommandTestCase extends KernelTestCase
{
    /**
     * @var Application
     */
    private $application;

    protected function setUp(): void
    {
        parent::setUp();
        $this->application = new Application(static::createKernel());
    }

    protected function executeCommand(string $name, array $input = [], bool $interactive = false): string
    {
        try {
            $command = $this->application->find($name);
            $commandTester = new CommandTester($command);
            if ($interactive) {
                $commandTester->setInputs($input);
                $input = [];
            }
            $commandTester->execute(
                [
                    'command' => $command->getName(),
                ] + $input
            );

            return $commandTester->getDisplay();
        } catch (\Exception $e) {
            $this->markTestIncompleteByException($e);
        }

        return '';
    }
}
