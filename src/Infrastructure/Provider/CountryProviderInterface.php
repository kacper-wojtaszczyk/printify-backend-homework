<?php
declare(strict_types=1);


namespace KacperWojtaszczyk\PrintifyBackendHomework\Infrastructure\Provider;


use KacperWojtaszczyk\PrintifyBackendHomework\Model\Order\Country;

interface CountryProviderInterface
{
    public function findByIp(IpAddress $ip): Country;
}