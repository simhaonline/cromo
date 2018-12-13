<?php

namespace App\Form\Type\General;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CasoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('asunto', TextType::class, ['data' => 'Reporte de ruta'])
            ->add('telefono', TextType::class, ['data' => $options['data']['telefono'] ? $options['data']['telefono'] : 0, 'required' => false])
            ->add('extension', TextType::class, ['required' => false, 'data' => 0])
            ->add('guardar', SubmitType::class, ['label' => 'Guardar', 'attr' => ['class' => 'btn btn-sm btn-primary']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // uncomment if you want to bind to a class
            //'data_class' => Caso::class,
        ]);
    }
}
