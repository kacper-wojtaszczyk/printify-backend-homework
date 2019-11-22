<?php

declare(strict_types=1);

namespace KacperWojtaszczyk\PrintifyBackendHomework\Model\User;

use KacperWojtaszczyk\PrintifyBackendHomework\Model\ValueObject;

final class EmailAddress implements ValueObject
{
    /**
     * @var string
     */
    private $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function equals(ValueObject $other): bool
    {
        if (!$other instanceof self) {
            return false;
        }

        return $this->value === $other->value;
    }
}
