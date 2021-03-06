<?php

namespace App\Form\Type\Inventario;

use App\Entity\Inventario\InvCotizacionTipo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CotizacionTipoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoCotizacionTipoPk',TextType::class,['label' => 'Codigo cotizacion tipo:','required' => true])
            ->add('nombre',TextType::class,['label' => 'Nombre:'])
            ->add('consecutivo',NumberType::class,['label' => 'Consecutivo:'])
            ->add('guardar', SubmitType::class,['label' => 'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => InvCotizacionTipo::class,
        ]);
    }

}
