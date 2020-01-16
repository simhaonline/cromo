<?php

namespace App\Form\Type\RecursoHumano;

use App\Entity\RecursoHumano\RhuEntidadExamen;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExamenEntidadType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre', TextType::class, array('required' => true))
            ->add('nit', TextType::class, array('required' => true))
            ->add('direccion', TextType::class, array('required' => true))
            ->add('telefono', TextType::class, array('required' => true))
            ->add('guardar', SubmitType::class, array('label' => 'Guardar'));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RhuEntidadExamen::class,
        ]);
    }

    public function getEstructuraPropiedadesLista()
    {
        $campos = '[
        {"campo":"codigoEntidadExamenPk",        "tipo":"pk",                "ayuda":"Codigo del registro",            "titulo":"ID"},
        {"campo":"nombre",                       "tipo":"texto",             "ayuda":"nombre",                         "titulo":"NOMBRE"},
        {"campo":"nit",                          "tipo":"texto",             "ayuda":"Numero de identificacion tributaria", "titulo":"NIT"},
        {"campo":"direccion",                    "tipo":"texto",             "ayuda":"Direccion de la entidad",        "titulo":"DIRECCION"},
        {"campo":"telefono",                     "tipo":"texto",             "ayuda":"Telefono de la entidad",         "titulo":"TELEFONO"}
        
           
        ]';
        return $campos;
    }

    public function getEstructuraPropiedadesFiltro()
    {
        $campos = '[
            {"child":"nombre",                "tipo":"TextType",          "propiedades":{"label":"nombre"}}

        ]';
        return $campos;
    }
}
