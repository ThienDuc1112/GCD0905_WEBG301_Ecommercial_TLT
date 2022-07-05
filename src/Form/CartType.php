<?php

namespace App\Form;

use App\Entity\Order;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CartType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
<<<<<<< Updated upstream:src/Form/OrderType.php
            ->add('Delivery_address')
            ->add('Order_date')
            ->add('Order_phone')
            ->add('Name_customer')
            ->add('Order_status');
=======
            ->add('items', CollectionType::class, [
                'entry_type' => AddToCartType::class
            ])
//            ->add('status')
//            ->add('createdAt')
        ;
>>>>>>> Stashed changes:src/Form/CartType.php
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
        ]);
    }
}
