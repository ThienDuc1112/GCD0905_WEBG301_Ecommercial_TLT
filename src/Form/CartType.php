<?php

namespace App\Form;

use App\Entity\Order;
use App\Form\EventListener\RemoveCartItemListener;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CartType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('items', CollectionType::class, [
                'entry_type' => AddToCartType::class,
            ])
            ->add('remove', SubmitType::class)
//            ->add('status')
//            ->add('createdAt')
        ;
        $builder->addEventSubscriber(new RemoveCartItemListener());

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
        ]);
    }
}
