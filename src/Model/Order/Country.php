<?php
declare(strict_types=1);

namespace KacperWojtaszczyk\PrintifyBackendHomework\Model\Order;

use KacperWojtaszczyk\PrintifyBackendHomework\Model\Order\Exception\InvalidCountryCodeException;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\ValueObject;

final class Country implements ValueObject
{
    /**
     * @var string
     */
    private $value;

    public function __construct(string $value)
    {
        if(!$this->isValid($value))
        {
            throw InvalidCountryCodeException::withCountryCode($value);
        }
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

    private function isValid(string $value): bool
    {
        return ctype_upper($value) && mb_strlen($value) === 2;
    }
}