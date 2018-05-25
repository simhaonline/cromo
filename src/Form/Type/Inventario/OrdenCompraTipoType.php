<?php

namespace App\Form\Type\Inventario;

use App\Entity\Inventario\InvOrdenCompraTipo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrdenCompraTipoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre')
            ->add('consecutivo')
            ->add('guardar',SubmitType::class)
            ->add('guardarnuevo', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => InvOrdenCompraTipo::class,
        ]);
    }
}
