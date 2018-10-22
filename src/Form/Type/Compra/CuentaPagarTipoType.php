<?php

namespace App\Form\Type\Compra;

use App\Entity\Compra\ComCuentaPagarTipo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CuentaPagarTipoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre')
            ->add('operacion')
            ->add('prefijo')
            ->add('codigoCuentaClienteFk')
            ->add('codigoCuentaRetencionIvaFk')
            ->add('codigoCuentaRetencionFuenteFk')
            ->add('codigoCuentaIndustriaComercioFk')
            ->add('codigoCuentaAjustePesoFk')
            ->add('codigoCuentaDescuentoFk')
            ->add('guardar', SubmitType::class, ['attr' => ['class' => 'btn btn-sm btn-primary']]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ComCuentaPagarTipo::class,
        ]);
    }
}
