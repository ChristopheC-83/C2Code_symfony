<?php

namespace App\Form;

use App\Entity\Courses;
use App\Entity\Lessons;
use App\Entity\LessonsTypes;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LessonsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('is_visible', null, [
                'label' => 'Visible',
                'mapped' => true,
                'required' => false,
                'attr' => ['id' => 'visible_field'],
            ])
            ->add('is_premium', null, [
                'label' => 'Premium',
                'mapped' => true,
                'attr' => ['id' => 'premium_field'],
            ])
            ->add('price', null, [ 
                'label' => 'Prix (en crédits)',
                'required' => false,
                'attr' => ['min' => 0],
            ])
            ->add('youtubeId', null, [
                'label' => 'ID YouTube',
                'required' => false,
            ])
            ->add('text', TextareaType::class, [
                'required' => false,
                'attr' => [
                    'id' => 'lessons_text',  
                    'class' => 'tinymce',
                ],
            ])

            // position obligatoire
            ->add('position', null, [
                'required' => true,
            ])


            ->add('time', TimeType::class, [
                'required' => false,
                'widget' => 'single_text', 
                'with_seconds' => true
            ])
            ->add('course', EntityType::class, [
                'class' => Courses::class,
                'choice_label' => 'title',
                'data' => $options['current_course'],
            ])
            ->add('type', EntityType::class, [
                'class' => LessonsTypes::class,
                'choice_label' => 'type',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Lessons::class,
            'current_course' => null,
        ]);
    }
}
