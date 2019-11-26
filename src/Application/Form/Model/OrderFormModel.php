<?php
declare(strict_types=1);

namespace KacperWojtaszczyk\PrintifyBackendHomework\Application\Form\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;


final class OrderFormModel
{
    /**
     * @var ArrayCollection|ItemFormModel[]
     *
     * @Assert\NotBlank(message="items should not be empty")
     */
    public $items;

}