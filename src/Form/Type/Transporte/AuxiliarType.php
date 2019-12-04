<?php

namespace App\Form\Type\Transporte;

use App\Entity\Transporte\TteAuxiliar;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AuxiliarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('numeroIdentificacion',NumberType::class,['required' => true,'label' => 'Numero identificacion:'])
            ->add('nombreCorto',TextType::class,['required' => true,'label' => 'Nombre completo:'])
            ->add('estadoInactivo',CheckboxType::class,['required' => false,'label' => 'Estado inactivo'])
            ->add('guardar', SubmitType::class, ['label'=>'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TteAuxiliar::class,
        ]);
    }

}
