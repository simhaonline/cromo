<?php


namespace App\Form\Type\RecursoHumano;

use App\Entity\RecursoHumano\RhuCapacitacion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CapacitacionType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RhuCapacitacion::class,
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fechaCapacitacion', DateTimeType::class, array('format' => 'yyyyMMdd','required' => false))
            ->add('tema', TextType::class, array('required' => false))
            ->add('numeroPersonasCapacitar', IntegerType::class, array('required' => false))
            ->add('vrCapacitacion', IntegerType::class, array('required' => false))
            ->add('lugar', TextType::class, array('required' => false))
            ->add('duracion', TextType::class, array('required' => false))
            ->add('objetivo', TextareaType::class, array('required' => false))
            ->add('contenido', TextareaType::class, array('required' => false))
            ->add('facilitador', TextType::class, array('required' => false))
            ->add('numeroIdentificacionFacilitador', TextType::class, array('required' => false))
            ->add('guardar', SubmitType::class, ['label'=>'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']]);

    }
}