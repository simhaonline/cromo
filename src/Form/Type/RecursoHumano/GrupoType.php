<?php

namespace App\Form\Type\RecursoHumano;

use App\Entity\RecursoHumano\RhuGrupo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GrupoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoGrupoPk',TextType::class,['label' => 'Codigo grupo:', 'required' => true])
            ->add('nombre',TextType::class,['label' => 'Nombre:', 'required' => true])
            ->add('guardar',SubmitType::class,['label' => 'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RhuGrupo::class,
        ]);
    }
}