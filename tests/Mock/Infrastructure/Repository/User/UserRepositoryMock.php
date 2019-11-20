<?php
declare(strict_types=1);

namespace KacperWojtaszczyk\PrintifyBackendHomework\Tests\Mock\Infrastructure\Repository\User;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\User\EmailAddress;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\User\User;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\User\UserId;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\User\UserRepositoryInterface;

class UserRepositoryMock implements UserRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {

    }

    public function findOneByEmailAddress(EmailAddress $email): ?User
    {
        return ;
    }

    public function findOneByUserId(UserId $userId): ?User
    {
        return ;
    }
}
