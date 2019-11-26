<?php
declare(strict_types=1);


namespace KacperWojtaszczyk\PrintifyBackendHomework\Tests\Functional\API;


use KacperWojtaszczyk\PrintifyBackendHomework\Tests\ApiTestCase;
use Symfony\Component\HttpFoundation\Response;

class CreateProductTest extends ApiTestCase
{
    public function testCreate()
    {
        $client = $this->createApiClient();
        $client->request('POST',
            '/api/product',
            [],
            [],
            [],
            '{"price": "100.00", "productType": "shirt", "color": "blue", "size": "L"}'
        );
        $response = $client->getResponse();
        $this->assertResponseIsSuccessful();
    }

    public function testCreateWrongParameters()
    {
        $client = $this->createApiClient();
        $client->request('POST', '/api/product', [], [], [], '{"price": "10000", "productType": "", "color": "", "": ""}');
        $response = $client->getResponse();
        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $data = json_decode($response->getContent(), true);
        $this->assertIsArray($data['error']);
    }

    public function testCreateDouble()
    {
        $client = $this->createApiClient();

        $client->request('POST',
            '/api/product',
            [],
            [],
            [],
            '{"price": "450.00", "productType": "type_1", "color": "blue", "size": "L"}'
        );
        $response = $client->getResponse();

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $data = json_decode($response->getContent(), true);
        $this->assertEquals('Product with type type_1 color blue and size L already exists.', $data['message']);

    }
}