<?php
declare(strict_types=1);

namespace KacperWojtaszczyk\PrintifyBackendHomework\Model\Order;

use Doctrine\ORM\Mapping as ORM;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\Price;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\Product\Product;

/**
 * @ORM\Entity()
 */
class OrderItem
{
    /**
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="Order", inversedBy="orderItem", cascade={"persist"})
     * @var Order
     */
    private $order;

    /**
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="KacperWojtaszczyk\PrintifyBackendHomework\Model\Product\Product")
     * @var Product
     */
    private $product;

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
     * @ORM\Column(type="integer")
     * @var int
     */
    private $quantity;


    public static function withParameters(
        Order $order,
        Product $product,
        Price $price,
        int $quantity
    ): self
    {
        $self = new self;
        $self->order = $order;
        $self->product = $product;
        $self->setPrice($price);
        $self->quantity = $quantity;
        return $self;
    }

    public function getOrder(): Order
    {
        return $this->order;
    }

    public function getTotal(): Price
    {
        return new Price($this->amount*$this->quantity, $this->currency);
    }

    public function getPrice(): Price
    {
        return new Price($this->amount, $this->currency);
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;
        return $this;
    }

    private function setPrice(Price $price): self
    {
        $this->amount = $price->getAmount();
        $this->currency = $price->getCurrency();
        return $this;
    }

}
