<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class EditUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class,[ 'label' => 'FirstName',])
            ->add('last_name',TextType::class,[ 'label' => 'LastName',])
            ->add('phone',TextType::class,[ 'label' => 'Phone Number',])
            ->add('location',TextType::class,[ 'label' => 'Location',])
            ->add('avatar', FileType::class, [
                'label' => 'Main Image',
                'mapped' => false, 'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                    ])
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
