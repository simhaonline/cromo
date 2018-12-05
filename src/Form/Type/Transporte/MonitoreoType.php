<?php

namespace App\Form\Type\Transporte;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MonitoreoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('field_name')
        ;
    }

    public function getEstructuraPropiedadesLista()
    {
        $campos = '[
            {"campo":"codigoMonitoreoPk",                       "tipo":"pk",        "ayuda":"Codigo de monitoreo",                      "titulo":"ID"},
            {"campo":"fechaInicio",                             "tipo":"fecha",     "ayuda":"Fecha inicio",                             "titulo":"INICIO"},
            {"campo":"fechaFin",                                "tipo":"fecha",     "ayuda":"Fecha fin",                                "titulo":"FIN"},
            {"campo":"codigoVehiculoFk",                        "tipo":"texto",     "ayuda":"Vehiculo",                                 "titulo":"VEHICULO"},
            {"campo":"soporte",                                 "tipo":"texto",     "ayuda":"Soporte",                                  "titulo":"SOPORTE"},
            {"campo":"codigoDespachoFk",                        "tipo":"texto",     "ayuda":"Despacho",                                 "titulo":"DESPACHO"},
            {"campo":"estadoAutorizado",                        "tipo":"bool",      "ayuda":"Autorizado",                               "titulo":"AUT"},
            {"campo":"estadoAprobado",                          "tipo":"bool",      "ayuda":"Aprobado",                                 "titulo":"APR"},
            {"campo":"estadoAnulado",                           "tipo":"bool",      "ayuda":"Anulado",                                  "titulo":"ANU"}
        ]';
        return $campos;

    }

    public function getEstructuraPropiedadesFiltro()
    {

        $campos = '[
            {"child":"fechaInicioDesde",                      "tipo":"DateType",   "propiedades":{"label":"Fecha Desde"}},
            {"child":"fechaFinHasta",                         "tipo":"DateType",   "propiedades":{"label":"Fecha Hasta"}},
            {"child":"estadoAutorizado",                      "tipo":"ChoiceType",   "propiedades":{"label":"Autorizado",     "choices":{"SI":true,"NO":false}}},
            {"child":"estadoAprobado",                        "tipo":"ChoiceType",   "propiedades":{"label":"Aprobado",       "choices":{"SI":true,"NO":false}}},
            {"child":"estadoAnulado",                         "tipo":"ChoiceType",   "propiedades":{"label":"Anulado",        "choices":{"SI":true,"NO":false}}}
        ]';

        return $campos;
    }

    public function getOrdenamiento(){
        $campos ='[
            {"campo":"fechaRegistro","tipo":"DESC"}
        ]';
        return $campos;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'App\Entity\Transporte\TteMonitoreo'
        ]);
    }
}