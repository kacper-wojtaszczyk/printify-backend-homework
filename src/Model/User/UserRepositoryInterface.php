<?php
declare(strict_types=1);


namespace KacperWojtaszczyk\PrintifyBackendHomework\Model\User;


interface UserRepositoryInterface
{
    public function findOneByEmailAddress(EmailAddress $email): ?User;
    public function findOneByUserId(UserId $userId): ?User;
    public function save(User $user): void;
}