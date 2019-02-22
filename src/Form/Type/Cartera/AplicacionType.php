<?php

namespace App\Form\Type\Cartera;

use App\Entity\Cartera\CarAplicacion;
use App\Entity\Cartera\CarCuentaCobrar;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AplicacionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoAplicacionPk');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CarAplicacion::class,
        ]);
    }

    public function getEstructuraPropiedadesLista()
    {
        $campos = '[
            {"campo":"codigoAplicacionPk",           "tipo":"pk",       "ayuda":"Codigo del registro",     "titulo":"ID"},
            {"campo":"numeroDocumento",                         "tipo":"texto", "ayuda":"Numero documento",            "titulo":"NUMERO"},
            {"campo":"numeroDocumentoAplicacion",                         "tipo":"texto", "ayuda":"Numedo del documento que esta aplicando",            "titulo":"NUMERO_A"},
            {"campo":"vrAplicacion",                         "tipo":"moneda","ayuda":"Valor de la aplicacion",          "titulo":"VR_APLICADO"},
            {"campo":"usuario",                        "tipo":"texto", "ayuda":"Usuario",                              "titulo":"USU"},
            {"campo":"estadoAutorizado",               "tipo":"bool",  "ayuda":"Autorizado",                           "titulo":"AUT"},
            {"campo":"estadoAprobado",                 "tipo":"bool",  "ayuda":"Aprobado",                             "titulo":"APR"},
            {"campo":"estadoAnulado",                  "tipo":"bool",  "ayuda":"Anulado",                              "titulo":"ANU"}                                  
        ]';
        return $campos;
    }

    public function getEstructuraPropiedadesFiltro()
    {
        $campos = '[
            {"child":"codigoAplicacionPk",    "tipo":"TextType",    "propiedades":{"label":"Codigo"}},
            {"child":"numeroDocumento",             "tipo":"TextType",  "propiedades":{"label":"Numero documento"}},
            {"child":"numeroDocumentoAplicacion",             "tipo":"TextType",  "propiedades":{"label":"Numero documento aplica"}}
        ]';
        return $campos;
    }
}