<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class ContactType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pseudo', TextType::class, [
                'label' => 'Pseudo',
                'required' => true,
                'constraints' => [
                    new Length([
                        'min' => 3,
                        'minMessage' => 'Votre prénom doit contenir au moins {{ limit }} caractères',
                    ]),
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'required' => true,
            ])

            ->add('message', TextareaType::class, [
                'label' => 'Message',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Votre message',
                    'rows' => 6,
                ],
            ])



            ->add('submit', SubmitType::class, [
                'label' => "Envoyer un message",
                'attr' => [
                    'class' => 'buttonValidation',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
