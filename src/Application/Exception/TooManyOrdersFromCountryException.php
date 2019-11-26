<?php
declare(strict_types=1);


namespace KacperWojtaszczyk\PrintifyBackendHomework\Application\Exception;


use KacperWojtaszczyk\PrintifyBackendHomework\Model\Order\Country;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;

class TooManyOrdersFromCountryException extends TooManyRequestsHttpException
{
    public static function withCountry(Country $country): TooManyOrdersFromCountryException
    {
        return new self(1, \sprintf('Too many request from country: %s', (string) $country));
    }
}