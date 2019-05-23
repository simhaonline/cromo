<?php


namespace App\Form\Type\RecursoHumano;


use App\Entity\RecursoHumano\RhuExamenDetalle;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExamenDetalleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fechaVence', DateType::class)
            ->add('validarVencimiento', ChoiceType::class, array('choices' => array('SI' => '1', 'NO' => '0')))
            ->add('comentario', TextareaType::class, array('required' => false))
            ->add('guardar', SubmitType::class);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data'=> RhuExamenDetalle::class,
        ]);
    }

}