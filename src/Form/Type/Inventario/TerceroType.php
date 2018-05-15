<?php

namespace App\Form\Type\Inventario;

use App\Entity\Inventario\InvTercero;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TerceroType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('digitoVerificacion')
            ->add('numeroIdentificacion',TextType::class,['label' => 'Identificación:'])
            ->add('nombreCorto',TextType::class,['label' => 'Nombre corto:'])
            ->add('nombres',TextType::class,['label' => 'Nombres:'])
            ->add('apellido1',TextType::class,['label' => 'Primer apellido:'])
            ->add('apellido2',TextType::class,['label' => 'Segundo apellido:'])
            ->add('plazoPago',TextType::class,['label' => 'Plazo:'])
            ->add('direccion',TextType::class,['label' => 'Dirección:'])
            ->add('telefono',TextType::class,['label' => 'Telefono'])
            ->add('celular',TextType::class,['label' => 'Celular'])
            ->add('email',TextType::class,['label' => 'Email:'])
            ->add('guardar',SubmitType::class,['label' => 'Guardar'])
            ->add('guardarnuevo',SubmitType::class,['label' => 'Guardar y nuevo'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => InvTercero::class,
        ]);
    }
}
