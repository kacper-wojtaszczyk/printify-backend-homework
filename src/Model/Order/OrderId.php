<?php

declare(strict_types=1);

namespace KacperWojtaszczyk\PrintifyBackendHomework\Model\Order;


use KacperWojtaszczyk\PrintifyBackendHomework\Model\UuidValueObjectTrait;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\ValueObject;
use Ramsey\Uuid\Uuid;

final class OrderId implements ValueObject
{
    use UuidValueObjectTrait;

}
