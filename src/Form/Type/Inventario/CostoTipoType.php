<?php

namespace App\Form\Type\Inventario;

use App\Entity\Inventario\InvCostoTipo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CostoTipoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoCostoTipoPk',TextType::class,['label' => 'Código bodega: '])
            ->add('nombre',TextType::class,['label' => 'Nombre: '])
            ->add('consecutivo',TextType::class,['label' => 'Consecitivo: '])
            ->add('codigoComprobanteFk',TextType::class,['label' => 'Código comprobante: '])
            ->add('prefijo',TextType::class,['label' => 'Prefijo: '])
            ->add('guardar', SubmitType::class, ['label'=>'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => InvCostoTipo::class,
        ]);
    }

}
