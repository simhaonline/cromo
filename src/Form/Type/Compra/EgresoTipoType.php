<?php

namespace App\Form\Type\Compra;

use App\Entity\Compra\ComEgresoTipo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EgresoTipoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoEgresoTipoPk', TextType::class, ['label' => 'Código:'])
            ->add('nombre', TextType::class, ['label' => 'Nombre:'])
            ->add('consecutivo', NumberType::class, ['label' => 'Consecutivo:'])
            ->add('guardar', SubmitType::class, ['attr' => ['class' => 'btn btn-sm btn-primary']]);;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ComEgresoTipo::class,
        ]);
    }
}
