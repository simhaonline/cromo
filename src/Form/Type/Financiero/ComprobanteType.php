<?php

namespace App\Form\Type\Financiero;

use App\Entity\Financiero\FinComprobante;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class ComprobanteType extends AbstractType
{


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("codigoComprobantePk", TextType::class, ['required' => true, 'label' => 'nombre'])
            ->add("nombre", TextType::class, ['required' => true, 'label' => 'nombre'])
            ->add('consecutivo', NumberType::class, array('required' => false))
            ->add('btnGuardar', SubmitType::class, ['label' => 'Guardar']);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FinComprobante::class,
        ]);
    }
}