<?php

namespace App\Form\Type\Crm;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VisitaTipoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoVisitaTipoPk',TextType::class, array('required' => true))
            ->add('nombre',TextType::class, array('required' => true))
            ->add('guardar', SubmitType::class,array('label'=>'Guardar'));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'App\Entity\Crm\CrmVisitaTipo'
        ]);
    }

    public function getEstructuraPropiedadesLista()
    {
        $campos = '[
            {"campo":"codigoVisitaTipoPk",                                           "tipo":"pk",         "ayuda":"Codigo control tipo",                 "titulo":"ID"},
            {"campo":"nombre",                                                      "tipo":"texto",      "ayuda":"Nombre",                              "titulo":"Nombre"}
        ]';
        return $campos;

    }

    public function getEstructuraPropiedadesFiltro()
    {

        $campos = '[
            {"child":"codigoVisitaTipoPk",                   "tipo":"TextType",      "propiedades":{"label":"Codigo"},     "operador":"like"},
            {"child":"nombre",                              "tipo":"TextType",      "propiedades":{"label":"Nombre"},     "operador":"like"}
        ]';

        return $campos;
    }
}
