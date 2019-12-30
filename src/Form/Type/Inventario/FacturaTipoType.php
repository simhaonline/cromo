<?php

namespace App\Form\Type\Inventario;

use App\Entity\Inventario\InvFacturaTipo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class FacturaTipoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoFacturaTipoPk',TextType::class,['label' => 'Codigo factura tipo:','required' => true])
            ->add('nombre',TextType::class,['label' => 'Nombre:','required' => true])
            ->add('consecutivo',TextType::class,['label' => 'Consecutivo: '])
            ->add('prefijo',TextType::class,['label' => 'Prefijo: ', 'required' => false])
            ->add('fechaDesdeVigencia', DateType::class, array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('fechaHastaVigencia', DateType::class, array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('numeracionDesde',TextType::class,['label' => 'Numeracion desde: '])
            ->add('numeracionHasta',TextType::class,['label' => 'Numeracion hasta: '])
            ->add('numeroResolucionDianFactura',TextareaType::class,['label' => 'Resolucion DIAN: '])
            ->add('informacionCuentaPago',TextareaType::class,['label' => 'Informacion cuenta de pago: '])
            ->add('notaCredito', CheckboxType::class, ['required' => false, 'label' => ''])
            ->add('guardar', SubmitType::class, ['label'=>'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => InvFacturaTipo::class,
        ]);
    }

}
