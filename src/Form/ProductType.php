<?php

namespace App\Form;

use App\Entity\Image;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Name')
            ->add('Price')
            ->add('Description')
            ->add('Brand')
            ->add('Category')
            ->add('Picture', FileType::class, [
                'label' => 'Main Image',
                'mapped' => false, 'constraints' => [
                    new File([
                        'maxSize' => '1024k',


                    ])
                ],
            ])
//            ->add('images_id', EntityType::class, [
//                'class' => Image::class,
//                'choice_label' => 'img1',
//                'mapped' => false,
//            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
