<?php

namespace App\Form\Type\General;

use App\Entity\General\GenReligion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ReligionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoReligionPk',TextType::class,['required' => true,'label' => 'codigo_religion_pk:'])
            ->add('nombre',TextType::class,['required' => true,'label' => 'Nombre:'])
            ->add('guardar', SubmitType::class, ['label'=>'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']])
            ->add('guardarnuevo', SubmitType::class, ['label'=>'Guardar y nuevo','attr' => ['class' => 'btn btn-sm btn-primary']]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => GenReligion::class,
        ]);
    }

}
