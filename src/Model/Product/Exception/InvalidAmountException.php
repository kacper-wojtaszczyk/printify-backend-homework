<?php
declare(strict_types=1);


namespace KacperWojtaszczyk\PrintifyBackendHomework\Model\Product\Exception;


use KacperWojtaszczyk\PrintifyBackendHomework\Model\Product\ProductId;

class InvalidAmountException extends \InvalidArgumentException
{
    public static function withAmount(int $amount): InvalidAmountException
    {
        return new self(\sprintf('Amount %d is incorrect.', $amount));
    }
}