<?php
declare(strict_types=1);


namespace KacperWojtaszczyk\PrintifyBackendHomework\Model\Product\Exception;


use KacperWojtaszczyk\PrintifyBackendHomework\Model\Product\Color;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\Product\ProductType;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\Product\Size;

class ProductAlreadyExistsException extends \InvalidArgumentException
{
    public static function withData(ProductType $productType, Color $color, Size $size): ProductAlreadyExistsException
    {
        return new self(
            \sprintf('Product with type %s color %s and size %s already exists.', $productType, $color, $size)
        );
    }
}