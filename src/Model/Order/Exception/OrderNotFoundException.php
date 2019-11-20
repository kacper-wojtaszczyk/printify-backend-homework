<?php
declare(strict_types=1);


namespace KacperWojtaszczyk\PrintifyBackendHomework\Model\Order\Exception;


use KacperWojtaszczyk\PrintifyBackendHomework\Model\Order\OrderId;

class OrderNotFoundException extends \InvalidArgumentException
{
    public static function withProductId(OrderId $orderId): OrderNotFoundException
    {
        return new self(\sprintf('Product with id %s cannot be found.', $orderId));
    }
}