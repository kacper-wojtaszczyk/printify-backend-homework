<?php
declare(strict_types=1);

namespace KacperWojtaszczyk\PrintifyBackendHomework\Tests\Mock\Infrastructure\Repository\Order;

use Doctrine\Common\Collections\ArrayCollection;
use Faker\Factory;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\Order\Country;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\Order\OrderId;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\Order\Order;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\Order\OrderItem;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\Order\OrderRepositoryInterface;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\Product\ProductId;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\Product\ProductType;
use KacperWojtaszczyk\PrintifyBackendHomework\Tests\Mock\Infrastructure\Repository\Product\ProductRepositoryMock;


class OrderRepositoryMock implements OrderRepositoryInterface
{
    /**
     * @var ArrayCollection|Order[]
     */
    private $orders;

    /**
     * @var \Faker\Generator
     */
    private $faker;

    /**
     * @var ProductRepositoryMock
     */
    private $productRepository;

    public function __construct(ProductRepositoryMock $productRepository)
    {
        $this->faker = Factory::create();
        $this->productRepository = $productRepository;
        $this->orders = $this->mockOrders(5);

    }

    public function findOneById(OrderId $id): ?Order
    {
        return $this->orders->get((string) $id);
    }

    public function getKeys(): array
    {
        return $this->orders->getKeys();
    }

    private function mockOrders(int $count): ArrayCollection
    {
        $orders = new ArrayCollection();
        $country = new Country('US');
        for($i = 0; $i <$count; $i++)
        {
            $order = Order::withParameters(
                OrderId::generate(),
                $country,
            );
            $product = $this->productRepository->findOneById(ProductId::fromString($this->productRepository->getKeys()[0]));

            $orderItem = OrderItem::withParameters(
                $order,
                $product,
                $product->getPrice(),
                1
            );
            $order->addOrderItem($orderItem);
        }

        return $orders;
    }

    public function save(Order $order): void
    {
        $this->orders->set((string) $order->getId(), $order);
    }

    public function findAll(): ?ArrayCollection
    {
        return $this->orders;
    }

    public function findByProductType(ProductType $type): ?ArrayCollection
    {
        $return = new ArrayCollection();
        foreach($this->orders as $order)
        {
            foreach($order->getOrderItem() as $item)
            {
                if($item->getProduct()->getProductType->equals($type))
                {
                    $return->add($order);
                }
            }

        }
        return $return;
    }
}
