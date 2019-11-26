<?php
declare(strict_types=1);

namespace KacperWojtaszczyk\PrintifyBackendHomework\Tests\Mock\Infrastructure\Repository\Product;

use Doctrine\Common\Collections\ArrayCollection;
use Faker\Factory;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\Price;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\Product\Color;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\Product\Product;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\Product\ProductId;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\Product\ProductRepositoryInterface;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\Product\ProductType;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\Product\Size;

class ProductRepositoryMock implements ProductRepositoryInterface
{
    /**
     * @var ArrayCollection|Product[]
     */
    private $products;

    /**
     * @var \Faker\Generator
     */
    private $faker;

    public function __construct()
    {
        $this->faker = Factory::create();
        $this->products = $this->mockProducts(5);
    }

    public function findOneById(ProductId $id): ?Product
    {
        return $this->products->get((string) $id);
    }

    public function getKeys(): array
    {
        return $this->products->getKeys();
    }

    private function mockProducts(int $count): ArrayCollection
    {
        $products = new ArrayCollection();
        for($i = 0; $i <$count; $i++)
        {
            $price = new Price($this->faker->numberBetween(10, 12000), $this->faker->currencyCode);
            $productType = new ProductType($this->faker->word);
            $color = new Color($this->faker->colorName);
            $size = new Size($this->faker->randomLetter);
            $productId = ProductId::fromProductTypeColorSize($productType, $color, $size);
            $product = Product::withParameters($productId, $price, $productType, $color, $size);
            $products->set((string) $productId, $product);
        }
        $product = $this->mockStaticProductForTest();
        $products->set((string) $product->getId(), $product);

        $product = $this->mockCheapProductForTest();
        $products->set((string) $product->getId(), $product);

        return $products;
    }

    private function mockStaticProductForTest()
    {
        $price = new Price(45000, 'USD');
        $productType = new ProductType('type_1');
        $color = new Color('blue');
        $size = new Size('L');
        $productId = ProductId::fromString('bd32155a-c49b-51d3-9b27-9bf627967c51');
        $product = Product::withParameters($productId, $price, $productType, $color, $size);

        return $product;
    }

    private function mockCheapProductForTest()
    {
        $price = new Price(50, 'USD');
        $productType = new ProductType('type_1');
        $color = new Color('red');
        $size = new Size('M');
        $productId = ProductId::fromString('dc68570a-0fc2-11ea-8d71-362b9e155667');
        $product = Product::withParameters($productId, $price, $productType, $color, $size);

        return $product;
    }

    public function findOneByTypeColorSize(ProductType $type, Color $color, Size $size): ?Product
    {
        foreach($this->products as $product)
        {
            if(
                $product->getSize()->equals($size)
                && $product->getColor()->equals($color)
                && $product->getProductType()->equals($type)
            )
            {
                return $product;
            }
        }
        return null;
    }

    public function save(Product $product): void
    {
        $this->products->set((string) $product->getId(), $product);
    }
}
