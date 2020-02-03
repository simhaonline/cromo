<?php

namespace App\Form\Type\Transporte;

use App\Entity\Transporte\TteRedespachoMotivo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RedespachoMotivoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoRedespachoMotivoPk',TextType::class, array('required' => true,'label'=>"Codigo redespacho motivo:"))
            ->add('nombre',TextType::class, array('required' => true,'label'=>'Nombre:'))
            ->add('btnGuardar', SubmitType::class,array('label'=>'Guardar','attr'=>['class'=>'btn btn-primary btn-sm']))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TteRedespachoMotivo::class,
        ]);
    }

}
