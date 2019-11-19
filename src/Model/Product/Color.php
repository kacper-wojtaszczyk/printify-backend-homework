<?php
declare(strict_types=1);

namespace KacperWojtaszczyk\PrintifyBackendHomework\Model\Product;

use KacperWojtaszczyk\PrintifyBackendHomework\Model\ValueObject;

final class Color implements ValueObject
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