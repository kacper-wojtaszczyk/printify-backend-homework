<?php
declare(strict_types=1);


namespace KacperWojtaszczyk\PrintifyBackendHomework\Model\Product\Exception;


use KacperWojtaszczyk\PrintifyBackendHomework\Model\Product\ProductId;

class InvalidCurrencyCodeException extends \InvalidArgumentException
{
    public static function withCurrencyCode(string $currency): InvalidCurrencyCodeException
    {
        return new self(\sprintf('Currency with code %s cannot be found.', $currency));
    }
}