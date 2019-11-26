<?php
declare(strict_types=1);

namespace KacperWojtaszczyk\PrintifyBackendHomework\Tests\Mock\Infrastructure\Provider;

use KacperWojtaszczyk\PrintifyBackendHomework\Infrastructure\Provider\CountryProviderInterface;
use KacperWojtaszczyk\PrintifyBackendHomework\Infrastructure\Provider\IpAddress;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\Order\Country;

class CountryProviderMock implements CountryProviderInterface
{
    private $sampleCountries = ['US', 'PL', 'LV'];

    public function findByIp(IpAddress $ip): Country
    {
        return new Country($this->sampleCountries[array_rand($this->sampleCountries, 1)]);
    }
}