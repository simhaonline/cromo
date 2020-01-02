<?php

namespace App\Form\Type\Transporte;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecaudoDevolucionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('field_name')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }

    public function getEstructuraPropiedadesLista()
    {
        $campos = '[	
            {"campo":"codigoRecaudoDevolucionPk",               "tipo":"pk",        "ayuda":"Codigo de recaudo devolucion",             "titulo":"ID"},	
            {"campo":"clienteRel.nombreCorto",                  "tipo":"texto",     "ayuda":"Tipo factura",                             "titulo":"CLIENTE",             "relacion":""},	
            {"campo":"fecha",                                   "tipo":"fecha",     "ayuda":"Fecha",                                    "titulo":"FECHA"},	
            {"campo":"vrTotal",                                 "tipo":"moneda",    "ayuda":"Total",                                    "titulo":"TOTAL"},	
            {"campo":"usuario",                                 "tipo":"texto",     "ayuda":"Usuario",                                  "titulo":"USUARIO"},	
            {"campo":"estadoAutorizado",                        "tipo":"bool",      "ayuda":"Autorizado",                               "titulo":"AUT"},	
            {"campo":"estadoAprobado",                          "tipo":"bool",      "ayuda":"Aprobado",                                 "titulo":"APR"},	
            {"campo":"estadoAnulado",                           "tipo":"bool",      "ayuda":"Anulado",                                  "titulo":"ANU"}	
        ]';
        return $campos;
    }
    public function getEstructuraPropiedadesFiltro()
    {
        $campos = '[	
            {"child":"codigoClienteFk",                 "tipo":"TextType",   "propiedades":{"label":"Cliente"}},	
            {"child":"estadoAutorizado",                "tipo":"ChoiceType",    "propiedades":{"label":"Autorizado",     "choices":{"SI":true,"NO":false}}},	
            {"child":"estadoAprobado",                "tipo":"ChoiceType",    "propiedades":{"label":"Aprobado",     "choices":{"SI":true,"NO":false}}},	
            {"child":"estadoAnulado",                "tipo":"ChoiceType",    "propiedades":{"label":"Anulado",     "choices":{"SI":true,"NO":false}}}	
        ]';
        return $campos;
    }
}
