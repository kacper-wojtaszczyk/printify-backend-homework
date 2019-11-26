<?php
declare(strict_types=1);


namespace KacperWojtaszczyk\PrintifyBackendHomework\Model\Exception;


class IncompatibleCurrenciesException extends \InvalidArgumentException
{
    public static function withCurrencies(string ...$currencies): IncompatibleCurrenciesException
    {
        return new self(\sprintf('You cannot add two different currencies %s and $s', $currencies));
    }
}