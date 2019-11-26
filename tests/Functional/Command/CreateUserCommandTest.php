<?php
declare(strict_types=1);


namespace KacperWojtaszczyk\PrintifyBackendHomework\Tests\Functional\Command;


use KacperWojtaszczyk\PrintifyBackendHomework\Tests\CommandTestCase;

class CreateUserCommandTest extends CommandTestCase
{
    public function testCreateUser()
    {
        $output = $this->executeCommand(
            'printify:user:create',
            ['username' => 'testing@example.org', 'password' => 'p4ssw0rd'],
            true
        );
        $this->assertStringContainsString('User testing@example.org created', $output);
    }

    public function testCreateUserAlreadyExists()
    {
        $output = $this->executeCommand(
            'printify:user:create',
            ['username' => 'test@example.org', 'password' => 'p4ssw0rd'],
            true
        );
        $this->assertStringContainsString('User with email test@example.org already exists', $output);
    }
}