<?php

declare(strict_types=1);

namespace KacperWojtaszczyk\PrintifyBackendHomework\Model\User;


use KacperWojtaszczyk\PrintifyBackendHomework\Model\UuidValueObjectTrait;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\ValueObject;
use Ramsey\Uuid\Uuid;

final class UserId implements ValueObject
{
    use UuidValueObjectTrait;
    private const NAMESPACE = '58906390-b7d3-4769-b4a3-0c4d45968dce';

    public static function fromEmail(EmailAddress $email): self
    {
        return new static(Uuid::uuid5(self::NAMESPACE, (string)$email));
    }
}
