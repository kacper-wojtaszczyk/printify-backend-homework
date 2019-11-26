<?php
declare(strict_types=1);


namespace KacperWojtaszczyk\PrintifyBackendHomework\Tests\Functional\API;


use KacperWojtaszczyk\PrintifyBackendHomework\Tests\ApiTestCase;
use Symfony\Component\HttpFoundation\Response;

class CreateOrderTest extends ApiTestCase
{
    public function testCreate()
    {
        $client = $this->createApiClient();
        $client->request('POST',
            '/api/order',
            [],
            [],
            [],
            '{"items": [{"productId": "bd32155a-c49b-51d3-9b27-9bf627967c51", "quantity": 3}, {"productId": "dc68570a-0fc2-11ea-8d71-362b9e155667", "quantity": 1}, {"productId": "dc68570a-0fc2-11ea-8d71-362b9e155667", "quantity": 2}]}'
        );
        $response = $client->getResponse();
        $this->assertResponseIsSuccessful();
        $data = json_decode($response->getContent(), true);
        $this->assertEquals('1351.50',$data['total']);
    }

    public function testCreateEmpty()
    {
        $client = $this->createApiClient();
        $client->request('POST',
            '/api/order',
            [],
            [],
            [],
            '{"items": []}'
        );
        $response = $client->getResponse();
        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $data = json_decode($response->getContent(), true);
        $this->assertEquals('items should not be empty',$data['error'][0]);
    }

    public function testCreateTooCheap()
    {
        $client = $this->createApiClient();
        $client->request('POST',
            '/api/order',
            [],
            [],
            [],
            '{"items": [{"productId": "dc68570a-0fc2-11ea-8d71-362b9e155667", "quantity": 1}]}'
        );
        $response = $client->getResponse();
        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $data = json_decode($response->getContent(), true);
        $this->assertEquals('Order has to be at least 10.00. 0.50 is too low',$data['message']);
    }

    public function testCreateWrongProduct()
    {
        $client = $this->createApiClient();
        $client->request('POST',
            '/api/order',
            [],
            [],
            [],
            '{"items": [{"productId": "dc68570a-0fc2-11ea-8d71-362bae155667", "quantity": 1}]}'
        );
        $response = $client->getResponse();
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
        $data = json_decode($response->getContent(), true);
        $this->assertEquals('Product with id dc68570a-0fc2-11ea-8d71-362bae155667 cannot be found.',$data['message']);
    }

}