<?php

declare(strict_types=1);

namespace KacperWojtaszczyk\PrintifyBackendHomework\Application\Form;

use KacperWojtaszczyk\PrintifyBackendHomework\Application\Form\Model\ProductFormModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class ProductForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->setMethod(Request::METHOD_POST);
        $builder->add('price', TextType::class);
        $builder->add('productType', TextType::class);
        $builder->add('color', TextType::class);
        $builder->add('size', TextType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProductFormModel::class,
            'csrf_protection' => false,
            'allow_extra_fields' => true,
        ]);
    }

    public function getBlockPrefix(): ?string
    {
        return null;
    }
}
