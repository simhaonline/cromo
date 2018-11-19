<?php

namespace App\Form\Type\RecursoHumano;

use App\Entity\RecursoHumano\RhuClasificacionRiesgo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class ClasificacionRiesgoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoClasificacionRiesgoPk',TextType::class,['required' => true,'label' => 'Codigo clasificación riesgo:'])
            ->add('nombre',TextType::class,['required' => true,'label' => 'Nombre:'])
            ->add('porcentaje',NumberType::class,['required' => true,'label' => 'Nombre:'])
            ->add('guardar', SubmitType::class, ['label'=>'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RhuClasificacionRiesgo::class,
        ]);
    }

    public function getEstructuraPropiedadesLista()
    {
        $campos = '[
            {"campo":"codigoClasificacionRiesgoPk", "tipo":"pk",    "ayuda":"Codigo del registro", "titulo":"ID"},
            {"campo":"nombre",                      "tipo":"texto", "ayuda":"Nombre del registro", "titulo":"NOMBRE"},
            {"campo":"porcentaje",                  "tipo":"texto", "ayuda":"Porcentaje",          "titulo":"%"}
        ]';
        return $campos;
    }

    public function getEstructuraPropiedadesExportar()
    {
        $campos = '[
            {"campo":"codigoClasificacionRiesgoPk", "tipo":"pk",    "ayuda":"Codigo del registro", "titulo":"ID"},
            {"campo":"nombre",                      "tipo":"texto", "ayuda":"Nombre del registro", "titulo":"NOMBRE"},
            {"campo":"porcentaje",                  "tipo":"texto", "ayuda":"Porcentaje",          "titulo":"%"}
        ]';
        return $campos;
    }
}
