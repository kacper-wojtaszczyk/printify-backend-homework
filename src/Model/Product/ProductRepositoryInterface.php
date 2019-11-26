<?php
declare(strict_types=1);


namespace KacperWojtaszczyk\PrintifyBackendHomework\Model\Product;


interface ProductRepositoryInterface
{
    public function findOneById(ProductId $id): ?Product;
    public function findOneByTypeColorSize(ProductType $type, Color $color, Size $size): ?Product;
    public function save(Product $product): void;
}