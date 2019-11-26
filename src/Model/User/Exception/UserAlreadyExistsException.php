<?php
declare(strict_types=1);


namespace KacperWojtaszczyk\PrintifyBackendHomework\Model\User\Exception;


use KacperWojtaszczyk\PrintifyBackendHomework\Model\User\UserId;

class UserAlreadyExistsException extends \InvalidArgumentException
{
    public static function withEmail(string $email): UserAlreadyExistsException
    {
        return new self(\sprintf('User with email %s already exists.', $email));
    }
}