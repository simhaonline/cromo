<?php

namespace App\Form\Type\RecursoHumano;

use App\Entity\RecursoHumano\RhuSolicitudMotivo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SolicitudMotivoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre', TextType::class, ['required' => false])
            ->add('guardar', SubmitType::class, ['label' => 'Guardar'])
            ->add('guardarnuevo', SubmitType::class, ['label' => 'Guardar y nuevo']);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RhuSolicitudMotivo::class,
        ]);
    }
}
