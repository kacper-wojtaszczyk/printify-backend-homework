<?php
declare(strict_types=1);


namespace KacperWojtaszczyk\PrintifyBackendHomework\Model\Product\Exception;


use KacperWojtaszczyk\PrintifyBackendHomework\Model\Product\ProductId;

class ProductNotFoundException extends \InvalidArgumentException
{
    public static function withProductId(ProductId $productId): ProductNotFoundException
    {
        return new self(\sprintf('Product with id %s cannot be found.', $productId));
    }
}