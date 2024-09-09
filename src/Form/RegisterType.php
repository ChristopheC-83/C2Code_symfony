<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

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
                    'placeholder' => 'Vous pourrez le modifier ! 😉'
                ],
                'constraints' => [
                    new Length([
                        'min' => 3,
                        'minMessage' => 'Votre pseudo doit contenir au moins {{ limit }} caractères',
                        'max' => 30,
                        'maxMessage' => 'Votre pseudo peut contenir au maximum {{ limit }} caractères',
                    ]),
                ],
            ])

            // ->add('password', PasswordType::class)

            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'constraints' => [
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} caractères',
                        'max' => 30,
                        'maxMessage' => 'Votre mot de passe peut contenir au maximum {{ limit }} caractères',
                    ]),
                ],

                'first_options' => [
                    'label' => 'Votre mot de passe',
                    'hash_property_path' => 'password',
                    'attr' => [
                        'placeholder' => 'On saisit son mot de passe...',
                    ],
                ],
                'second_options' => [
                    'label' => 'Confirmation votre mot de passe',
                    'attr' => [
                        'placeholder' => '... et on vérifie son mot de passe !',
                    ],
                ],
                // on explique à Symfo qu'il n'y a pas de lien entre ce formulaire et l'entity lié au formulaire
                // on récupere 'password' dans 'hash_property_path'
                'mapped' => false,
            ])

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
            'constraints' => [
                new UniqueEntity([
                    'entityClass' => User::class,
                    'fields' => 'email',
                    'message' => 'Cette adresse email est déjà utilisée',
                ]),
                new UniqueEntity([
                    'entityClass' => User::class,
                    'fields' => 'pseudo',
                    'message' => 'Ce pseudo est déjà utilisé, il va falloir en choisir un autre !',
                ]),
            ],
        ]);
    }
}
