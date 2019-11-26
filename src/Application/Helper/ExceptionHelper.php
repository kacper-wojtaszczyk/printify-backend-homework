<?php
declare(strict_types=1);

namespace KacperWojtaszczyk\PrintifyBackendHomework\Application\Helper;

use KacperWojtaszczyk\PrintifyBackendHomework\Application\Exception\IncorrectOrderTotalException;
use KacperWojtaszczyk\PrintifyBackendHomework\Application\Exception\TooManyOrdersFromCountryException;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\Order\Exception\InvalidCountryCodeException;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\Order\Exception\OrderNotFoundException;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\Product\Exception\InvalidAmountException;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\Product\Exception\InvalidCurrencyCodeException;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\Product\Exception\ProductAlreadyExistsException;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\Product\Exception\ProductNotFoundException;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\User\Exception\UserNotFoundException;
use Symfony\Component\HttpFoundation\Response;

class ExceptionHelper
{
    public static function mapToHttpCode(\Throwable $e): int
    {
        switch(get_class($e))
        {
            case InvalidCountryCodeException::class:
            case InvalidAmountException::class:
            case InvalidCurrencyCodeException::class:
            case ProductAlreadyExistsException::class:
            case IncorrectOrderTotalException::class:
                return Response::HTTP_BAD_REQUEST;
                break;
            case OrderNotFoundException::class:
            case ProductNotFoundException::class:
            case UserNotFoundException::class:
                return Response::HTTP_NOT_FOUND;
                break;
            case TooManyOrdersFromCountryException::class:
                return Response::HTTP_TOO_MANY_REQUESTS;
                break;
            default:
                return Response::HTTP_INTERNAL_SERVER_ERROR;
                break;
        }
    }
}