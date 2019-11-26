<?php
declare(strict_types=1);


namespace KacperWojtaszczyk\PrintifyBackendHomework\Model\Order;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="KacperWojtaszczyk\PrintifyBackendHomework\Infrastructure\Repository\Order\OrderRequestRepository")
 */
final class OrderRequest

{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     * @var string
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $country;

    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    private $date;

    public static function withParameters(
        Country $country,
        \DateTime $date
    ): self
    {
        $self = new self;
        $self->country = (string) $country;
        $self->date = $date;
        return $self;
    }

}