<?php

declare(strict_types=1);

namespace KacperWojtaszczyk\PrintifyBackendHomework\Tests;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase as SymfonyKernelTestCase;

class KernelTestCase extends SymfonyKernelTestCase
{
    protected function setUp(): void
    {
        self::bootKernel();
    }

    public function markTestIncompleteByException(\Exception $e): void
    {

        $message = $this->prepareMessage(get_class($e), $e->getCode(), $e->getMessage());

        $this->markTestIncomplete($message);
    }

    private function prepareMessage(string $className, int $code, string $message): string
    {
        return sprintf('Test returned %s with code %s%s%s', $className, $code, PHP_EOL, $message);
    }
}
