<?php

declare(strict_types=1);

namespace KacperWojtaszczyk\PrintifyBackendHomework\Unit\Model\User;


use Faker\Factory;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\Price;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\Product\Color;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\Product\Product;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\Product\ProductId;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\Product\ProductType;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\Product\Size;
use KacperWojtaszczyk\PrintifyBackendHomework\Tests\KernelTestCase;

class ProductTest extends KernelTestCase
{
    /**
     * @var \Faker\Generator
     */
    private $faker;
    /**
     * @var Price
     */
    private $price;
    /**
     * @var ProductType
     */
    private $productType;
    /**
     * @var Color
     */
    private $color;
    /**
     * @var Size
     */
    private $size;
    /**
     * @var ProductId
     */
    private $productId;

    public function setUp(): void
    {
        parent::setUp();
        $this->faker = Factory::create();
        $this->price = new Price($this->faker->numberBetween(10, 12000), $this->faker->currencyCode);
        $this->productType = new ProductType($this->faker->word);
        $this->color = new Color($this->faker->colorName);
        $this->size = new Size($this->faker->randomLetter);
        $this->productId = ProductId::fromProductTypeColorSize($this->productType, $this->color, $this->size);
    }

    public function testInitializeProduct()
    {


        $product = Product::withParameters($this->productId, $this->price, $this->productType, $this->color, $this->size);

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
