<?php
declare(strict_types=1);

namespace KacperWojtaszczyk\PrintifyBackendHomework\Infrastructure\Provider;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;
use KacperWojtaszczyk\PrintifyBackendHomework\Infrastructure\Exception\InvalidResponseException;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\Order\Country;
use Webmozart\Assert\Assert;

class ApilityCountryProvider implements CountryProviderInterface
{

    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @var string
     */
    private $fallbackCountry;

    public function __construct(ClientInterface $client, string $fallbackCountry)
    {
        $this->client = $client;
        $this->fallbackCountry = $fallbackCountry;
    }

    public function findByIp(IpAddress $ip): Country
    {
        try {
            $request = new Request('GET', sprintf('/geoip/%s', (string) $ip));
            $response = $this->client->send($request);
            $data = json_decode(strval($response->getBody()), true);

            if(!$this->isValid($data))
            {
                throw InvalidResponseException::withJson(json_encode($data));
            }

            return new Country($data['ip']['country']);
        } catch (GuzzleException|\JsonException $e)
        {
            return new Country($this->fallbackCountry);
        }
    }

    private function isValid(array $data)
    {
        return isset($data['ip']['country']);
    }
}