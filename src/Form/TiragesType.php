<?php

namespace App\Form;

use App\Entity\Tirages;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TiragesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('tir_date', null, [
                'widget' => 'single_text',
            ])
            ->add('tir_1')
            ->add('tir_2')
            ->add('tir_3')
            ->add('tir_4')
            ->add('tir_5')
            ->add('tir_c')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tirages::class,
        ]);
    }
}
