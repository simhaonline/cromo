<?php

namespace App\Form\Type\Contabilidad;

use App\Entity\Contabilidad\CtbCuenta;
use Symfony\Component\Form\AbstractType;
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
            ->add('guardar', SubmitType::class, ['label' => 'Guardar', 'attr' => ['class' => 'btn btn-sm btn-primary']])
            ->add('guardarnuevo', SubmitType::class, ['label' => 'Guardar y nuevo', 'attr' => ['class' => 'btn btn-sm btn-primary']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CtbCuenta::class,
        ]);
    }
}
