<?php
declare(strict_types=1);


namespace KacperWojtaszczyk\PrintifyBackendHomework\Tests\Unit\Infrastructure;


use Faker\Factory;
use KacperWojtaszczyk\PrintifyBackendHomework\Infrastructure\Provider\ApilityCountryProvider;
use KacperWojtaszczyk\PrintifyBackendHomework\Infrastructure\Provider\IpAddress;
use KacperWojtaszczyk\PrintifyBackendHomework\Infrastructure\Repository\Product\ProductRepository;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\Order\Country;
use KacperWojtaszczyk\PrintifyBackendHomework\Tests\KernelTestCase;

class ApilityCountryProviderTest extends KernelTestCase
{
    /**
     * @var \GuzzleHttp\Client
     */
    private $guzzleClient;

    public function setUp(): void
    {
        $kernel = self::bootKernel();
        $this->faker = Factory::create();
        $this->guzzleClient = $kernel->getContainer()->get('eight_points_guzzle.client.apility');
    }

    public function testGetByIp()
    {
        $apility = new ApilityCountryProvider($this->guzzleClient, 'US');
        $country = $apility->findByIp(new IpAddress('77.79.195.34'));

        $this->assertTrue($country->equals(new Country('PL')));
    }
}