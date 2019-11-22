<?php

declare(strict_types=1);

namespace KacperWojtaszczyk\PrintifyBackendHomework\Unit\Model\Order;


use Doctrine\Common\Collections\ArrayCollection;
use Faker\Factory;
use KacperWojtaszczyk\PrintifyBackendHomework\Infrastructure\Repository\Product\ProductRepository;
use KacperWojtaszczyk\PrintifyBackendHomework\Infrastructure\Repository\User\UserRepository;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\Order\Country;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\Order\Exception\InvalidCountryCodeException;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\Order\Order;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\Order\OrderId;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\Order\OrderItem;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\Price;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\Product\Product;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\Product\ProductId;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\Product\ProductRepositoryInterface;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\User\User;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\User\UserId;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\User\UserRepositoryInterface;
use KacperWojtaszczyk\PrintifyBackendHomework\Tests\KernelTestCase;
use KacperWojtaszczyk\PrintifyBackendHomework\Tests\Mock\Infrastructure\Repository\Product\ProductRepositoryMock;
use KacperWojtaszczyk\PrintifyBackendHomework\Tests\Mock\Infrastructure\Repository\User\UserRepositoryMock;

class OrderTest extends KernelTestCase
{
    /**
     * @var \Faker\Generator
     */
    private $faker;

    /**
     * @var ProductRepositoryInterface|ProductRepositoryMock
     */
    private $productRepository;

    /**
     * @var UserRepositoryInterface|UserRepositoryMock
     */
    private $userRepository;


    public function setUp(): void
    {
        $kernel = self::bootKernel();
        $this->faker = Factory::create();
        $this->productRepository = $kernel->getContainer()->get(ProductRepository::class);
        $this->userRepository = $kernel->getContainer()->get(UserRepository::class);

    }

    public function testInitializeOrder()
    {
        $orderId = OrderId::generate();
        $userId = UserId::fromString($this->userRepository->getKeys()[0]);
        $user = $this->userRepository->findOneByUserId($userId);
        $country = new Country('US');
        $order = Order::withParameters($orderId, $country);
        $order->setUser($user);

        $this->assertTrue($order->getId()->equals($orderId));
        $this->assertTrue($order->getCountry()->equals($country));
        $this->assertInstanceOf(User::class, $order->getUser());
    }

    public function testOrderWithItems()
    {
        $order = $this->createOrder();
        $items = new ArrayCollection();
        $counter = 0;
        foreach($this->productRepository->getKeys() as $id)
        {
            $product = $this->productRepository->findOneById(ProductId::fromString($id));
            $price = new Price($this->faker->numberBetween(1000, 2000), "USD");
            $quantity = $this->faker->numberBetween(1,10);

            $orderItem = OrderItem::withParameters($order, $product, $price, $quantity);
            $items->add($orderItem);
            $counter++;
        }

        $order->setOrderItem($items);

        $this->assertTrue($order->getOrderItem()->count() === $counter);

        /** @var OrderItem $orderItem */
        $orderItem = $order->getOrderItem()->first();

        $this->assertInstanceOf(Product::class, $orderItem->getProduct());
        $this->assertInstanceOf(Price::class, $orderItem->getPrice());
        $this->assertInstanceOf(Order::class, $orderItem->getOrder());
        $this->assertIsInt($orderItem->getQuantity());
    }

    public function testCountryException()
    {
        try {
            new Country($this->faker->colorName);
        } catch (\Exception $e)
        {
            $this->assertInstanceOf(InvalidCountryCodeException::class, $e);
        }
    }

    private function createOrder(): Order
    {
        $orderId = OrderId::generate();
        $userId = UserId::fromString($this->userRepository->getKeys()[0]);
        $user = $this->userRepository->findOneByUserId($userId);
        $country = new Country('US');
        $order = Order::withParameters($orderId, $country);
        $order->setUser($user);
        return $order;
    }

}
