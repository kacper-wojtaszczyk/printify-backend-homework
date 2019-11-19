<?php

declare(strict_types=1);

namespace KacperWojtaszczyk\PrintifyBackendHomework\Unit\Model\User;


use Faker\Factory;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\User\EmailAddress;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\User\Password;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\User\User;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\User\UserId;
use KacperWojtaszczyk\PrintifyBackendHomework\Tests\KernelTestCase;

class UserTest extends KernelTestCase
{
    public function testInitializeUser()
    {
        $faker = Factory::create();
        $email = new EmailAddress($faker->email);
        $password = new Password($faker->password);
        $userId = UserId::fromEmail($email);

        $user = User::withCredentials($userId, $email, $password);

        $this->assertTrue($user->getId()->equals($userId));
        $this->assertTrue($user->getEmail()->equals($email));
        $this->assertTrue($user->getPassword()->equals($password));
    }
}
