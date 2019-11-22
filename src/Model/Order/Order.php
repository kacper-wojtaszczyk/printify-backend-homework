<?php
declare(strict_types=1);

namespace KacperWojtaszczyk\PrintifyBackendHomework\Model\Order;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use KacperWojtaszczyk\PrintifyBackendHomework\Model\User\User;

/**
 * @ORM\Entity(repositoryClass="KacperWojtaszczyk\PrintifyBackendHomework\Infrastructure\Repository\Order\OrderRepository")
 */
final class Order
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
     * @ORM\OneToMany(targetEntity="OrderItem", mappedBy="order")
     * @var ArrayCollection|OrderItem[]
     */
    private $orderItem;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @var User
     */
    private $user;

    public static function withParameters(
        OrderId $orderId,
        Country $country
    ): self
    {
        $self = new self;
        $self->id = (string) $orderId;
        $self->country = (string) $country;
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

    public function getOrderItem(): ArrayCollection
    {
        return $this->orderItem;
    }

    public function setOrderItem(ArrayCollection $orderItem): self
    {
        $this->orderItem = $orderItem;
        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;
        return $this;
    }

}
