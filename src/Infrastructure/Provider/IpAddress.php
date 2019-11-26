<?php
declare(strict_types=1);

namespace KacperWojtaszczyk\PrintifyBackendHomework\Infrastructure\Provider;


use KacperWojtaszczyk\PrintifyBackendHomework\Model\ValueObject;

class IpAddress implements ValueObject
{
    /**
     * @var string|null
     */
    private $value;

    public function __construct(string $ip)
    {
        if($this->isValid($ip))
        {
            $this->value = $ip;
        }
    }

    public function equals(ValueObject $other): bool
    {
        if(!($other instanceof self))
        {
            return false;
        }
        return $this->value === $other->value;
    }

    public function isValid(string $ip): bool
    {
        return filter_var($ip, FILTER_VALIDATE_IP) === $ip;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}