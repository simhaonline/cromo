<?php

namespace App\Form\Type\Transporte;

use App\Entity\Transporte\TteDespachoTipo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DespachoTipoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoDespachoTipoPk')
            ->add('nombre')
            ->add('consecutivo')
            ->add('exigeNumero')
            ->add('generaMonitoreo')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TteDespachoTipo::class,
        ]);
    }
}
