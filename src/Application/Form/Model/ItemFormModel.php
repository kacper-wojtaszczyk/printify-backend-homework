<?php
declare(strict_types=1);

namespace KacperWojtaszczyk\PrintifyBackendHomework\Application\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;


final class ItemFormModel
{
    /**
     * @var string
     *
     * @Assert\NotBlank(message="productId is mandatory")
     * @Assert\Uuid(message="productId should be a valid UUID")
     */
    public $productId;

    /**
     * @var int
     *
     * @Assert\NotBlank(message="quantity is mandatory")
     * @Assert\Positive(message="quantity should be a positive number")
     */
    public $quantity;

}