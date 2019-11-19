<?php
declare(strict_types=1);


namespace KacperWojtaszczyk\PrintifyBackendHomework\Model\Product;


interface ProductRepositoryInterface
{
    public function findOneById(ProductId $email): ?Product;
}