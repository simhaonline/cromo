<?php

namespace App\Form\Type\Financiero;

use App\Entity\Financiero\FinCuenta;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CuentaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoCuentaPk', TextType::class, ['label' => 'Codigo cuenta:'])
            ->add('nombre', TextType::class, ['label' => 'Nombre:'])
            ->add('exigeTercero', CheckboxType::class, ['label' => 'Exige tercero:', 'required' => false])
            ->add('exigeCentroCosto', CheckboxType::class, ['label' => 'Exige centro de costo', 'required' => false])
            ->add('exigeBase', CheckboxType::class, ['label' => 'Exige base', 'required' => false])
            ->add('exigeDocumentoReferencia', CheckboxType::class, ['label' => 'Exige documento referencia', 'required' => false])
            ->add('permiteMovimiento', CheckboxType::class, ['label' => 'Permite movimientos', 'required' => false])
            ->add('guardar', SubmitType::class, ['label' => 'Guardar', 'attr' => ['class' => 'btn btn-sm btn-primary']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FinCuenta::class,
        ]);
    }

}
