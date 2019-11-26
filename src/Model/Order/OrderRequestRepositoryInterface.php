<?php
declare(strict_types=1);


namespace KacperWojtaszczyk\PrintifyBackendHomework\Model\Order;


use Doctrine\Common\Collections\ArrayCollection;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\Product\ProductType;

interface OrderRequestRepositoryInterface
{
    public function findByDateCountry(\DateTime $date, Country $country): ?ArrayCollection;
    public function save(OrderRequest $request): void;
}