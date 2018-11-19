<?php

namespace App\Form\Type\RecursoHumano;

use App\Entity\RecursoHumano\RhuSolicitudExperiencia;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SolicitudExperienciaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre',TextType::class,['required' => true,'label' => 'Nombre:'])
            ->add('guardar', SubmitType::class, ['label'=>'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RhuSolicitudExperiencia::class,
        ]);
    }

    public function getEstructuraPropiedadesLista()
    {
        $campos = '[
            {"campo":"nombre",        "tipo":"texto", "ayuda":"Nombre del registro", "titulo":"NOMBRE"}
        ]';
        return $campos;
    }

    public function getEstructuraPropiedadesExportar()
    {
        $campos = '[
            {"campo":"nombre",        "tipo":"texto", "ayuda":"Nombre del registro", "titulo":"NOMBRE"}
        ]';
        return $campos;
    }
}
