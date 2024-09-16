<?php

namespace App\Form;

use App\Entity\Articles;
use App\Entity\Languages;
use App\Entity\Types;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticlesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('visible')
            ->add('thumbnail')
            ->add('pitch')
            ->add('text')
            ->add('types', EntityType::class, [
                'class' => Types::class,
                'choice_label' => 'id',
            ])
            ->add('languages', EntityType::class, [
                'class' => Languages::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Articles::class,
        ]);
    }
}
