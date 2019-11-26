<?php

declare(strict_types=1);

namespace KacperWojtaszczyk\PrintifyBackendHomework\Tests\Unit\Application\Helper;

use KacperWojtaszczyk\PrintifyBackendHomework\Application\Exception\TooManyOrdersFromCountryException;
use KacperWojtaszczyk\PrintifyBackendHomework\Application\Helper\ExceptionHelper;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\Order\Exception\InvalidCountryCodeException;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\Order\Exception\OrderNotFoundException;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\Product\Exception\InvalidAmountException;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\Product\Exception\InvalidCurrencyCodeException;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\Product\Exception\ProductAlreadyExistsException;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\Product\Exception\ProductNotFoundException;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\User\Exception\UserNotFoundException;
use KacperWojtaszczyk\PrintifyBackendHomework\Tests\KernelTestCase;
use Symfony\Component\HttpFoundation\Response;

class ExceptionHelperTest extends KernelTestCase
{
    public function testExceptionMapping()
    {
        $exceptions = [
            ['e' => new InvalidCountryCodeException(), 'code' => 400],
            ['e' => new InvalidAmountException(), 'code' => 400],
            ['e' => new InvalidCurrencyCodeException(), 'code' => 400],
            ['e' => new ProductAlreadyExistsException(), 'code' => 400],
            ['e' => new OrderNotFoundException(), 'code' => 404],
            ['e' => new ProductNotFoundException(), 'code' => 404],
            ['e' => new UserNotFoundException(), 'code' => 404],
            ['e' => new TooManyOrdersFromCountryException(), 'code' => 429],
            ['e' => new \Exception(), 'code' => 500],
        ];
        foreach($exceptions as $exception)
        {
            $this->assertEquals(ExceptionHelper::mapToHttpCode($exception['e']), $exception['code']);
        }
    }
}