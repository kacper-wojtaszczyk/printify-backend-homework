<?php
declare(strict_types=1);


namespace KacperWojtaszczyk\PrintifyBackendHomework\Model\Order;


use Doctrine\Common\Collections\ArrayCollection;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\Product\ProductType;

interface OrderRepositoryInterface
{
    public function findOneById(OrderId $id): ?Order;
    public function findAll(): ?ArrayCollection;
    public function findByProductType(ProductType $type): ?ArrayCollection;
    public function save(Order $order): void;
}