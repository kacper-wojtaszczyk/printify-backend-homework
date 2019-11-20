<?php

declare(strict_types=1);

namespace KacperWojtaszczyk\PrintifyBackendHomework\Unit\Model\Order;


use Faker\Factory;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\Price;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\Product\Product;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\User\User;
use KacperWojtaszczyk\PrintifyBackendHomework\Tests\KernelTestCase;

class OrderTest extends KernelTestCase
{
    /**
     * @var \Faker\Generator
     */
    private $faker;
    /**
     * @var Product
     */
    private $product;
    /**
     * @var User
     */
    private $user;


    public function setUp(): void
    {
        parent::setUp();
        $this->faker = Factory::create();
        $price = new Price($this->faker->numberBetween(10, 12000), $this->faker->currencyCode);
        $productType = new ProductType($this->faker->word);
        $color = new Color($this->faker->colorName);
        $size = new Size($this->faker->randomLetter);
        $productId = ProductId::fromProductTypeColorSize($this->productType, $this->color, $this->size);
        $product = Product::withParameters($this->productId, $this->price, $this->productType, $this->color, $this->size);
    }

    public function testInitializeProduct()
    {




        $this->assertTrue($product->getPrice()->equals($this->price));
        $this->assertTrue($product->getProductType()->equals($this->productType));
        $this->assertTrue($product->getColor()->equals($this->color));
        $this->assertTrue($product->getSize()->equals($this->size));
        $this->assertTrue($product->getId()->equals($this->productId));

    }

    public function testMutablePrice()
    {
        $product = Product::withParameters($this->productId, $this->price, $this->productType, $this->color, $this->size);

        $this->assertTrue($product->getPrice()->equals($this->price));
        $price = new Price($this->faker->numberBetween(10, 12000), $this->faker->currencyCode);
        $product->setPrice($price);
        $this->assertTrue($product->getPrice()->equals($price));
    }
}
