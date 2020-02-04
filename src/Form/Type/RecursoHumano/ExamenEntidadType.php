<?php

namespace App\Form\Type\RecursoHumano;

use App\Entity\RecursoHumano\RhuExamenEntidad;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExamenEntidadType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre', TextType::class, array('required' => true))
            ->add('nit', TextType::class, array('required' => true))
            ->add('direccion', TextType::class, array('required' => true))
            ->add('telefono', TextType::class, array('required' => true))
            ->add('btnGuardar', SubmitType::class, array('label' => 'Guardar'));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RhuExamenEntidad::class,
        ]);
    }

}
