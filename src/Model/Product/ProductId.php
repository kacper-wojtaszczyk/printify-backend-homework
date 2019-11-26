<?php

declare(strict_types=1);

namespace KacperWojtaszczyk\PrintifyBackendHomework\Model\Product;


use KacperWojtaszczyk\PrintifyBackendHomework\Model\UuidValueObjectTrait;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\ValueObject;
use Ramsey\Uuid\Uuid;

final class ProductId implements ValueObject
{
    use UuidValueObjectTrait;

    public static function fromProductTypeColorSize(ProductType $productType, Color $color, Size $size): self
    {
        $namespace = Uuid::uuid5('00000000-0000-0000-0000-000000000000', (string) $productType);
        return new static(Uuid::uuid5($namespace, ((string )$color) . ((string) $size)));
    }
}
