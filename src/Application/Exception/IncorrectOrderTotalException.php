<?php
declare(strict_types=1);


namespace KacperWojtaszczyk\PrintifyBackendHomework\Application\Exception;


use KacperWojtaszczyk\PrintifyBackendHomework\Model\Order\OrderId;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\Price;

class IncorrectOrderTotalException extends \InvalidArgumentException
{
    public static function withPrice(Price $price): IncorrectOrderTotalException
    {
        return new self(\sprintf('Order has to be at least 10.00. %s is too low', (string) $price));
    }
}