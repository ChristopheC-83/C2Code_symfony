<?php

namespace App\Form;

use App\Entity\Courses;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CoursesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('language')
            ->add('title')
           
            ->add('is_visible', CheckboxType::class, [
                'required' => false,
                // 'mapped' => false,
            ])
            ->add('logo')
            ->add('pitch')
            ->add('position');
            if ($options['data']->getSlug() !== null ) {
                $builder->add('slug', TextType::class, [
                    'label' => 'Slug',
                    'required' => false,
                ]);
            }
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Courses::class,
        ]);
    }
}
