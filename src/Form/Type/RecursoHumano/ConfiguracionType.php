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
            ->add('vrSalarioMinimo', NumberType::class, ['required' => false])
            ->add('codigoConceptoAuxilioTransporteFk', TextType::class, ['required' => true])
            ->add('vrAuxilioTransporte', NumberType::class, ['required' => true]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RhuConfiguracion::class,
        ]);
    }
}
