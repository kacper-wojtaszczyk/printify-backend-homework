<?php
declare(strict_types=1);


namespace KacperWojtaszczyk\PrintifyBackendHomework\Tests\Functional\API;


use KacperWojtaszczyk\PrintifyBackendHomework\Tests\ApiTestCase;
use Symfony\Component\HttpFoundation\Response;

class ListOrderTest extends ApiTestCase
{
    public function testList()
    {
        $client = $this->createApiClient();
        $client->request('GET', '/api/order');
        $response = $client->getResponse();
        $this->assertResponseIsSuccessful();
        $data = json_decode($response->getContent(), true);
        $this->assertIsArray($data);
    }

    public function testListByProductType()
    {
        $client = $this->createApiClient();
        $client->request('GET', '/api/order/type_1',);
        $response = $client->getResponse();
        $this->assertResponseIsSuccessful();
        $data = json_decode($response->getContent(), true);
        $this->assertIsArray($data);
    }
}