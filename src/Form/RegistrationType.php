<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username')
            ->add('email')
            ->add('password')
            ->add('roles', ChoiceType::class, [

                'choices' => [

                    'User ' => 'ROLE_USER',

                    'Administrator ' => 'ROLE_ADMIN'

                ],

                'expanded' => true,

                'multiple' => true,

                'label' => 'Roles',

                'empty_data' => ['ROLE_USER'],

            ]);;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}
