<?php

namespace App\Form\Type\Transporte;

use App\Entity\Transporte\TteFacturaTipo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class FacturaTipoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoFacturaTipoPk',TextType::class,['required' => true,'label' => 'Codigo factura:'])
            ->add('nombre',TextType::class,['required' => true,'label' => 'Nombre:'])
            ->add('consecutivo',NumberType::class,['required' => true,'label' => 'Consecutivo:'])
            ->add('prefijo',TextType::class,['required' => false,'label' => 'Prefijo:'])
            ->add('resolucionFacturacion',TextType::class,['required' => false,'label' => 'Resolucion:'])
            ->add('guiaFactura', CheckboxType::class, array('required'  => false))
            ->add('codigoCuentaCobrarTipoFk',TextType::class,['required' => false,'label' => 'Codigo cuenta cobrar tipo fk:'])
            ->add('codigoFacturaClaseFk',TextType::class,['required' => false,'label' => 'Codigo factura clase fk:'])
            ->add('codigoCuentaIngresoFleteFk',TextType::class,['required' => false,'label' => 'Codigo cuenta ingreso flete fk:'])
            ->add('codigoCuentaIngresoManejoFk',TextType::class,['required' => false,'label' => 'Codigo cuenta ingreso manejo fk:'])
            ->add('codigoCuentaClienteFk',TextType::class,['required' => false,'label' => 'Codigo cuenta cliente fk:'])
            ->add('naturalezaCuentaIngreso',TextType::class,['required' => false,'label' => 'Naturaleza cuenta ingreso:'])
            ->add('naturalezaCuentaCliente',TextType::class,['required' => false,'label' => 'Naturaleza cuenta cliente:'])
            ->add('codigoComprobanteFk',TextType::class,['required' => false,'label' => 'Codigo comprobante:'])
            ->add('guardar', SubmitType::class, ['label'=>'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']])
            ->add('guardarnuevo', SubmitType::class, ['label'=>'Guardar y nuevo','attr' => ['class' => 'btn btn-sm btn-primary']]);;
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TteFacturaTipo::class,
        ]);
    }
}

