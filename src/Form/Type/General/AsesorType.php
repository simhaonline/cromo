<?php

namespace App\Form\Type\General;

use App\Entity\General\GenAsesor;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AsesorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('numeroIdentificacion',NumberType::class,['label' => 'Identificacion:', 'required' => true])
            ->add('nombre',TextType::class,['label' => 'Nombre:','required' => true])
            ->add('direccion',TextType::class,['label' => 'Direccion:','required' => true])
            ->add('telefono',TextType::class,['label' => 'Telefono:', 'required' => true])
            ->add('celular',TextType::class,['label' => 'Celular:', 'required' => true])
            ->add('email',TextType::class,['label' => 'Email:', 'required' => true])
            ->add('usuario',TextType::class,['label' => 'Usuario:', 'required' => false])
            ->add('guardar', SubmitType::class, ['label'=>'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => GenAsesor::class,
        ]);
    }

}
