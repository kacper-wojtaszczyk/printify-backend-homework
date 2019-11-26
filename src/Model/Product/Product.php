<?php
declare(strict_types=1);

namespace KacperWojtaszczyk\PrintifyBackendHomework\Model\Product;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\Order\OrderItem;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\Price;

/**
 * @ORM\Entity(repositoryClass="KacperWojtaszczyk\PrintifyBackendHomework\Infrastructure\Repository\Product\ProductRepository")
 */
class Product
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="guid")
     * @var string
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @var int
     */
    private $amount;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $currency;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $productType;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $color;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $size;



    public static function withParameters(
        ProductId $productId,
        Price $price,
        ProductType $productType,
        Color $color,
        Size $size
    ): self
    {
        $self = new self;
        $self->id = (string) $productId;
        $self->setPrice($price);
        $self->productType = (string) $productType;
        $self->color = (string) $color;
        $self->size = (string) $size;
        return $self;
    }

    public function getId(): ProductId
    {
        return ProductId::fromString($this->id);
    }

    public function getPrice(): Price
    {
        return new Price($this->amount, $this->currency);
    }

    public function getProductType(): ProductType
    {
        return new ProductType($this->productType);
    }

    public function getColor(): Color
    {
        return new Color($this->color);
    }

    public function getSize(): Size
    {
        return new Size($this->size);
    }

    public function setPrice(Price $price): self
    {
        $this->amount = $price->getAmount();
        $this->currency = $price->getCurrency();
        return $this;
    }

}
