<?php
declare(strict_types=1);


namespace KacperWojtaszczyk\PrintifyBackendHomework\Model\Order;


interface OrderRepositoryInterface
{
    public function findOneById(Order $email): ?Order;
}