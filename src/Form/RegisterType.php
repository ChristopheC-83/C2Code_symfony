<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Votre adresse email',
                'attr' => [
                    'placeholder' => 'Merci de saisir votre adresse email'
                ]
               
                
            ])
            ->add('pseudo', TextType::class, [
                'label' => 'Le pseudo vu par les membres',
                'attr' => [
                    'placeholder' => 'Vous pourrez le modifier 😉'
                ]])
            ->add('password', PasswordType::class)
            ->add('submit', SubmitType::class, [
                'label' => 'S\'inscrire', 
                'attr' => [
                    'class' => 'buttonValidation'

                ]
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
