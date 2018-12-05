<?php

namespace App\Form\Type\General;

use App\Entity\General\GenImagen;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImagenesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("btnGuardar", SubmitType::class, ['label' => " Guardar", 'attr' => ['class' => 'btn btn-default btn-sm']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => GenImagen::class,
        ]);
    }
}
