<?php

namespace App\Form\Type\RecursoHumano;

use App\Entity\RecursoHumano\RhuConfiguracion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConfiguracionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('vrSalario', NumberType::class, ['required' => false])
            ->add('codigoAuxilioTransporte', TextType::class, ['required' => true])
            ->add('vrAuxilioTransporte', NumberType::class, ['required' => true])
            ->add('guardar', SubmitType::class, ['label' => 'Guardar', 'attr' => ['class' => 'btn btn-sm btn-primary']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RhuConfiguracion::class,
        ]);
    }
}
