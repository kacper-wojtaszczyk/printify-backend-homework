<?php
declare(strict_types=1);


namespace KacperWojtaszczyk\PrintifyBackendHomework\Model\Order\Exception;


class InvalidCountryCodeException extends \InvalidArgumentException
{
    public static function withCountryCode(string $code): InvalidCountryCodeException
    {
        return new self(\sprintf('Country code should be exactly two uppercase letters. "%s" given', $code));
    }
}