<?php
declare(strict_types=1);


namespace KacperWojtaszczyk\PrintifyBackendHomework\Tests;


use KacperWojtaszczyk\PrintifyBackendHomework\Infrastructure\Security\User;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiTestCase extends WebTestCase
{
    protected function setUp(): void
    {
        self::bootKernel();
    }

    protected function createSimpleClient(): KernelBrowser
    {
        $options = [];
        $server = [
            'HTTP_ACCEPT' => 'application/json',
            'HTTP_CONTENT_TYPE' => 'application/json',
            'CONTENT_TYPE' => 'application/json',
        ];

        return static::createClient($options, $server);
    }

    protected function createApiClient(): KernelBrowser
    {
        $options = [];
        $server = [
            'HTTP_Authorization' => $this->generateApiToken(),
            'HTTP_ACCEPT' => 'application/json',
            'HTTP_CONTENT_TYPE' => 'application/json',
            'CONTENT_TYPE' => 'application/json',
        ];

        return static::createClient($options, $server);
    }

    private function generateApiToken(): string
    {
        $tokenManager = static::$container->get('lexik_jwt_authentication.jwt_manager');
        $user = new User('test@example.org', 'qwerty');

        return $tokenManager->create($user);
    }
}