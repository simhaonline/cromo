<?php

namespace App\Form\Type\Transporte;

use App\Entity\Transporte\TteConductor;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ConductorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('numeroIdentificacion',NumberType::class,['required' => true,'label' => 'Numero identificacion:'])
            ->add('nombreCorto',TextType::class,['required' => true,'label' => 'Nombre:'])
            ->add('nombre1',TextType::class,['required' => true,'label' => 'Primer nombre:'])
            ->add('nombre2',TextType::class,['required' => true,'label' => 'Segundo nombre:'])
            ->add('apellido1',TextType::class,['required' => true,'label' => 'Primer apellido:'])
            ->add('apellido2',TextType::class,['required' => true,'label' => 'Segundo apellido:'])
            ->add('direccion',TextType::class,['required' => true,'label' => 'Direccion:'])
            ->add('fechaNacimiento', DateType::class,array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('telefono',NumberType::class,['required' => true,'label' => 'Telefono:'])
            ->add('movil',NumberType::class,['required' => true,'label' => 'Celular:'])
            ->add('numeroLicencia',NumberType::class,['required' => true,'label' => 'Licencia:'])
            ->add('categoriaLicencia',TextType::class,['required' => true,'label' => 'Categoria:'])
            ->add('fechaVenceLicencia', DateType::class,array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('barrio',TextType::class,['required' => true,'label' => 'Barrio:'])
            ->add('alias',TextType::class,['required' => true,'label' => 'Alias:'])
            ->add('codigoVehiculo',TextType::class,['required' => true,'label' => 'Codigo vehiculo:'])
            ->add('comentario',TextareaType::class,['required' => true,'label' => 'Comentarios:'])
            ->add('guardar', SubmitType::class, ['label'=>'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']])
            ->add('guardarnuevo', SubmitType::class, ['label'=>'Guardar y nuevo','attr' => ['class' => 'btn btn-sm btn-primary']]);;
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TteConductor::class,
        ]);
    }
}
