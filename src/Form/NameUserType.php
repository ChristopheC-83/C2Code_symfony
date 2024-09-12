<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class NameUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('pseudo', TextType::class, [
            'label' => 'Votre nouveau pseudo sera ...',
            'constraints' => [
                new Length([
                    'min' => 3,
                    'minMessage' => 'Votre prénom doit contenir au moins {{ limit }} caractères',
                ]),
            ],
            'attr' => [
                'placeholder' => '{{app.user.pseudo}}',
            ],
        ])

        ->add('submit', SubmitType::class, [
            'label' => "Modifier le pseudo",
            'attr' => [
                'class' => 'buttonValidation',
            ],
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
