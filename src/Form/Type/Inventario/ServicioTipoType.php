<?php

namespace App\Form\Type\Inventario;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ServicioTipoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoServicioTipoPk',TextType::class, array('required' => true))
            ->add('nombre',TextType::class, array('required' => true))
            ->add('guardar', SubmitType::class,array('label'=>'Guardar'));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'App\Entity\Inventario\InvServicioTipo'
        ]);
    }

    public function getEstructuraPropiedadesLista()
    {
        $campos = '[
            {"campo":"codigoServicioTipoPk",                                        "tipo":"pk",         "ayuda":"Codigo control tipo",                 "titulo":"ID"},
            {"campo":"nombre",                                                      "tipo":"texto",      "ayuda":"Nombre",                              "titulo":"Nombre"}
        ]';
        return $campos;

    }

    public function getEstructuraPropiedadesFiltro()
    {

        $campos = '[
            {"child":"codigoServicioTipoPk",                "tipo":"TextType",      "propiedades":{"label":"Codigo"},     "operador":"like"},
            {"child":"nombre",                              "tipo":"TextType",      "propiedades":{"label":"Nombre"},     "operador":"like"}
        ]';

        return $campos;
    }
}
