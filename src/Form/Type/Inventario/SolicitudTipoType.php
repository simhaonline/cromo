<?php

namespace App\Form\Type\Inventario;

use App\Entity\Inventario\InvSolicitudTipo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SolicitudTipoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoSolicitudTipoPk',TextType::class,['label' => 'Codigo solicitud tipo:','required' => true])
            ->add('nombre',TextType::class,['label' => 'Nombre:'])
            ->add('consecutivo',NumberType::class,['label' => 'Consecutivo:'])
            ->add('btnGuardar', SubmitType::class, ['label' => 'Guardar'])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => InvSolicitudTipo::class,
        ]);
    }

}
