<?php

namespace App\Form\Type\General;

use App\Entity\General\GenMovimientoComentario;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MovimientoComentarioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoMovimientoFk', TextType::class,['attr' => ['readonly' => 'readonly']])
            ->add('codigoModeloFk', TextType::class,['attr' => ['readonly' => 'readonly']])
            ->add('descripcion', TextareaType::class, ['attr' => ['rows' => '6']])
            ->add('guardar',SubmitType::class,['label' => 'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => GenMovimientoComentario::class,
        ]);
    }
}
