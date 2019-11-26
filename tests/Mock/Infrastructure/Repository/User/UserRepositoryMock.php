<?php
declare(strict_types=1);

namespace KacperWojtaszczyk\PrintifyBackendHomework\Tests\Mock\Infrastructure\Repository\User;

use Doctrine\Common\Collections\ArrayCollection;
use Faker\Factory;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\User\EmailAddress;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\User\Password;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\User\User;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\User\UserId;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\User\UserRepositoryInterface;

class UserRepositoryMock implements UserRepositoryInterface
{
    /**
     * @var ArrayCollection
     */
    private $usersById;

    /**
     * @var ArrayCollection
     */
    private $usersByEmail;

    /**
     * @var \Faker\Generator
     */
    private $faker;

    public function __construct()
    {
        $this->faker = Factory::create();
        $this->mockUsers(1);
    }


    public function findOneByEmailAddress(EmailAddress $email): ?User
    {
        return $this->usersByEmail->get((string) $email);
    }

    public function findOneByUserId(UserId $userId): ?User
    {
        return $this->usersById->get((string) $userId);
    }

    public function getKeys(): array
    {
        return $this->usersById->getKeys();
    }

    private function mockUsers(int $count): void
    {
        $this->usersById = new ArrayCollection();
        $this->usersByEmail = new ArrayCollection();
        for($i = 0; $i <$count; $i++)
        {
            $email = new EmailAddress($this->faker->email);
            $password = new Password($this->faker->password);
            $userId = UserId::fromEmail($email);

            $user = User::withCredentials($userId, $email, $password);
            $this->usersById->set((string) $userId, $user);
            $this->usersByEmail->set((string) $email, $user);
        }
        $user = $this->mockStaticUserForApi();
        $this->usersById->set((string) $user->getId(), $user);
        $this->usersByEmail->set((string) $user->getEmail(), $user);
    }

    private function mockStaticUserForApi(): User
    {
        $email = new EmailAddress('test@example.org');
        $password = new Password('qwerty');
        $userId = UserId::fromEmail($email);

        $user = User::withCredentials($userId, $email, $password);
        return $user;
    }

    public function save(User $user): void
    {
        $this->usersById->set((string)$user->getId(), $user);
        $this->usersByEmail->set((string)$user->getEmail(), $user);
    }
}
