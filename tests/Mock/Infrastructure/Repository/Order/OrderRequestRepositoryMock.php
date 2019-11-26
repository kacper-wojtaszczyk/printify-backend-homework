<?php
declare(strict_types=1);

namespace KacperWojtaszczyk\PrintifyBackendHomework\Tests\Mock\Infrastructure\Repository\Order;

use Doctrine\Common\Collections\ArrayCollection;
use Faker\Factory;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\Order\Country;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\Order\Order;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\Order\OrderRequest;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\Order\OrderRequestRepositoryInterface;;


class OrderRequestRepositoryMock implements OrderRequestRepositoryInterface
{
    /**
     * @var ArrayCollection|OrderRequest[]
     */
    private $orderRequests;

    /**
     * @var \Faker\Generator
     */
    private $faker;

    public function __construct()
    {
        $this->faker = Factory::create();
        $this->orderRequests = $this->mockOrderRequests(9);
    }

    private function mockOrderRequests(int $count): ArrayCollection
    {
        $orderRequests = new ArrayCollection();
        $country = new Country('US');
        for($i = 0; $i <$count; $i++)
        {
            $orderRequest = OrderRequest::withParameters(
                $country,
                new \DateTime()
            );
            $orderRequests->add($orderRequest);
        }

        return $orderRequests;
    }

    public function save(OrderRequest $orderRequest): void
    {
        $this->orderRequests->add($orderRequest);
    }

    public function findByDateCountry(\DateTime $date, Country $country): ?ArrayCollection
    {
        return $this->orderRequests;
    }
}
