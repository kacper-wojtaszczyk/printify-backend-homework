<?php
declare(strict_types=1);

namespace KacperWojtaszczyk\PrintifyBackendHomework\Application\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;


final class ProductFormModel
{
    /**
     * @var string
     *
     * @Assert\NotBlank(message="Amount should not be empty")
     * @Assert\Regex(pattern="/[0-9]*\.[0-9]{2}/", message="Amount should be given in format '000.00'")
     */
    public $price;

    /**
     * @var string
     *
     * @Assert\NotBlank(message="productType is mandatory")
     */
    public $productType;

    /**
     * @var string
     *
     * @Assert\NotBlank(message="color is mandatory")
     */
    public $color;

    /**
     * @var string
     *
     * @Assert\NotBlank(message="size is mandatory")
     */
    public $size;

}