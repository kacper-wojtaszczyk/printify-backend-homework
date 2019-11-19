<?php
declare(strict_types=1);

namespace KacperWojtaszczyk\PrintifyBackendHomework\Infrastructure\Repository\User;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\User\EmailAddress;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\User\User;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\User\UserId;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\User\UserRepositoryInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements UserRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function findOneByEmailAddress(EmailAddress $email): ?User
    {
        return $this->createQueryBuilder('u')
            ->where('u.email = :email')
            ->setParameter('email', $email)
            ->getQuery()->getOneOrNullResult();
    }

    public function findOneByUserId(UserId $userId): ?User
    {
        return $this->createQueryBuilder('u')
            ->where('u.id = :id')
            ->setParameter('id', (string) $userId)
            ->getQuery()->getOneOrNullResult();
    }
}
