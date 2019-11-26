<?php

declare(strict_types=1);

namespace KacperWojtaszczyk\PrintifyBackendHomework\Application\Form;

use KacperWojtaszczyk\PrintifyBackendHomework\Application\Form\Model\OrderFormModel;
use KacperWojtaszczyk\PrintifyBackendHomework\Application\Form\Model\ProductFormModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class OrderForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->setMethod(Request::METHOD_POST);
        $builder->add('items', CollectionType::class, [
            'entry_type' => ItemForm::class,
            'allow_add' => true
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => OrderFormModel::class,
            'csrf_protection' => false,
            'allow_extra_fields' => true,
        ]);
    }

    public function getBlockPrefix(): ?string
    {
        return null;
    }
}
