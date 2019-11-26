<?php
declare(strict_types=1);

namespace KacperWojtaszczyk\PrintifyBackendHomework\Model\Order;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\Price;

/**
 * @ORM\Entity(repositoryClass="KacperWojtaszczyk\PrintifyBackendHomework\Infrastructure\Repository\Order\OrderRepository")
 * @ORM\Table(name="`order`")
 */
class Order implements \JsonSerializable
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="guid")
     * @var string
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $country;

    /**
     * @ORM\OneToMany(targetEntity="OrderItem", mappedBy="order", cascade={"persist", "remove"})
     * @var Collection|OrderItem[]
     */
    private $orderItem;


    public static function withParameters(
        OrderId $orderId,
        Country $country
    ): self
    {
        $self = new self;
        $self->id = (string) $orderId;
        $self->country = (string) $country;
        $self->orderItem = new ArrayCollection();
        return $self;
    }

    public function getId(): OrderId
    {
        return OrderId::fromString($this->id);
    }

    public function getCountry(): Country
    {
        return new Country($this->country);
    }

    public function getOrderItem(): Collection
    {
        return $this->orderItem;
    }

    public function addOrderItem(OrderItem $orderItem): self
    {
        if($temp = $this->orderItem->get((string)$orderItem->getProduct()->getId()))
        {
            $orderItem->setQuantity($orderItem->getQuantity() + $temp->getQuantity());
            $this->orderItem->set((string)$orderItem->getProduct()->getId(), $orderItem);
        }
        $this->orderItem->set((string)$orderItem->getProduct()->getId(), $orderItem);
        return $this;
    }

    public function getTotal(): ?Price
    {
        $total = null;
        foreach($this->orderItem as $item)
        {
            if($total === null)
            {
                $total = $item->getTotal();
            } else
            {
                $total = $total->add($item->getTotal());
            }
        }
        return $total;
    }

    public function jsonSerialize()
    {
        $items = [];
        foreach ($this->getOrderItem() as $item)
        {
            $items[] = [
                'productId' => (string) $item->getProduct()->getId(),
                'price' => (string) $item->getPrice(),
                'quantity' => $item->getQuantity()
            ];
        }
        return[
            'id' => (string) $this->getId(),
            'total' => (string) $this->getTotal(),
            'items' => $items
        ];
    }
}
