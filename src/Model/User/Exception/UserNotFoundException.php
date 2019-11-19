<?php
declare(strict_types=1);


namespace KacperWojtaszczyk\PrintifyBackendHomework\Model\User\Exception;


use KacperWojtaszczyk\PrintifyBackendHomework\Model\User\UserId;

class UserNotFoundException extends \InvalidArgumentException
{
    public static function withUserId(UserId $userId): UserNotFoundException
    {
        return new self(\sprintf('User with id %s cannot be found.', $userId));
    }

    public static function withEmail(string $email): UserNotFoundException
    {
        return new self(\sprintf('User with email %s cannot be found.', $email));
    }
}