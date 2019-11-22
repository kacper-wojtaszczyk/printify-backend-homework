<?php

declare(strict_types=1);

namespace KacperWojtaszczyk\PrintifyBackendHomework\Unit\Model\User;

use Faker\Factory;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\Order\Country;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\Order\OrderId;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\Product\Color;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\Price;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\Product\ProductId;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\Product\ProductType;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\Product\Size;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\User\EmailAddress;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\User\Password;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\User\UserId;
use KacperWojtaszczyk\PrintifyBackendHomework\Tests\KernelTestCase;
use KacperWojtaszczyk\PrintifyBackendHomework\Tests\Mock\Model\ValueObjectMock;

class ValueObjectTest extends KernelTestCase
{
    /**
     * @var ValueObjectMock
     */
    private $mock;
    /**
     * @var \Faker\Generator
     */
    private $faker;

    public function setUp(): void
    {
        parent::setUp();
        $this->faker = Factory::create();
        $this->mock = new ValueObjectMock("MOCK");
    }

    public function testCompareUser()
    {
        $email = new EmailAddress($this->faker->email);
        $password = new Password($this->faker->password);
        $userId = UserId::generate();

        $this->assertFalse($email->equals($this->mock));
        $this->assertFalse($password->equals($this->mock));
        $this->assertFalse($userId->equals($this->mock));

    }

    public function testCompareProduct()
    {

        $productId = ProductId::generate();
        $currency = $this->faker->currencyCode;
        $amount = $this->faker->numberBetween(10, 12000);
        $price = new Price($amount, $currency);
        $productType = new ProductType($this->faker->word);
        $color = new Color($this->faker->colorName);
        $size = new Size($this->faker->randomLetter);

        $money = $price->getMoney();

        $this->assertFalse($productId->equals($this->mock));
        $this->assertFalse($price->equals($this->mock));
        $this->assertFalse($productType->equals($this->mock));
        $this->assertFalse($color->equals($this->mock));
        $this->assertFalse($size->equals($this->mock));
        $this->assertTrue((Price::fromMoney($money))->equals($price));
        $this->assertEquals((string) $price, $amount . " " . $currency);

    }

    public function testCompareOrder()
    {

        $orderId = OrderId::generate();
        $country = new Country($this->faker->countryCode);

        $this->assertFalse($orderId->equals($this->mock));
        $this->assertFalse($country->equals($this->mock));


    }
}
