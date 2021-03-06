<?php

namespace App\Form\Type\Inventario;

use App\Entity\Inventario\InvConfiguracion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ConfiguracionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('informacionPagoMovimiento',TextareaType::class,['label' => 'Información pago'])
            ->add('codigoFormatoMovimiento',TextType::class,['label' => 'Código formato'])
            ->add('codigoDocumentoMovimientosSalidaBodega',TextType::class,['label' => 'Documento salida de bodega'])
            ->add('codigoDocumentoMovimientosEntradaBodega',TextType::class,['label' => 'Documento entrada de bodega'])
            ->add('validarBodegaUsuario',CheckboxType::class,['required' => false])
            ->add('guardar',SubmitType::class,['label' => 'Actualizar','attr' => ['class' => 'btn btn-sm btn-primary']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => InvConfiguracion::class,
        ]);
    }
}
