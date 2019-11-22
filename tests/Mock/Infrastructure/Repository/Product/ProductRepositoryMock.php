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
     * @var ArrayCollection
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

    public function findOneById(ProductId $productId): ?Product
    {
        return $this->products->get((string) $productId);
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
        return $products;
    }

    public function findOneByTypeColorSize(ProductType $type, Color $color, Size $size): ?Product
    {
        return null;
    }

    public function save(Product $product): void
    {
        $this->products->add($product);
    }
}
