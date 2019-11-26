<?php
declare(strict_types=1);

namespace KacperWojtaszczyk\PrintifyBackendHomework\Infrastructure\Exception;

use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

class InvalidResponseException extends \JsonException
{
    public static function withJson(string $json): InvalidResponseException
    {
        return new self(\sprintf('Country query failed with message: `%s`', $json));
    }
}