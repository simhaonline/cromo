<?php

namespace App\Form\Type\Tesoreria;

use App\Entity\Compra\ComCuentaPagar;
use App\Entity\Tesoreria\TesCuentaPagar;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CuentaPagarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoProveedorFk')
            ->add('codigoCuentaPagarTipoFk')
            ->add('codigoDocumento')
            ->add('numeroDocumento')
            ->add('modulo')
            ->add('numeroReferencia')
            ->add('soporte')
            ->add('fechaFactura')
            ->add('fechaVence')
            ->add('plazo')
            ->add('vrSubtotal')
            ->add('vrIva')
            ->add('vrTotal')
            ->add('vrAbono')
            ->add('vrSaldo')
            ->add('vrSaldoOperado')
            ->add('operacion')
            ->add('vrRetencionFuente')
            ->add('vrRetencionIva')
            ->add('estadoAnulado')
            ->add('diasVencimiento')
            ->add('comentario')
            ->add('proveedorRel')
            ->add('cuentaPagarTipoRel')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TesCuentaPagar::class,
        ]);
    }

    public function getEstructuraPropiedadesLista()
    {
        $campos = '[
            {"campo":"codigoCuentaPagarPk",            "tipo":"pk",    "ayuda":"Codigo del registro",             "titulo":"ID"},
            {"campo":"numeroDocumento",                         "tipo":"texto", "ayuda":"Consecutivo de aprobación",            "titulo":"NUMERO"},
            {"campo":"cuentaPagarTipoRel.nombre",           "tipo":"texto", "ayuda":"Tipo recibo",                          "titulo":"TIPO",          "relacion":""},
            {"campo":"fechaFactura",                          "tipo":"fecha", "ayuda":"Fecha",                                "titulo":"FECHA"},
            {"campo":"fechaVence",                      "tipo":"fecha", "ayuda":"Fecha de pago",                        "titulo":"FECHA_PAGO"},
            {"campo":"terceroRel.numeroIdentificacion","tipo":"texto", "ayuda":"Numero de identificacion del tercero", "titulo":"IDENTIFICACION","relacion":""},
            {"campo":"terceroRel.nombreCorto",         "tipo":"texto", "ayuda":"Nombre del tercero",                   "titulo":"NOMBRE",        "relacion":""},
            {"campo":"vrTotal",                    "tipo":"moneda","ayuda":"Total",                                "titulo":"TOTAL"},
            {"campo":"estadoAutorizado",               "tipo":"bool",  "ayuda":"Autorizado",                           "titulo":"AUT"},
            {"campo":"estadoAprobado",                 "tipo":"bool",  "ayuda":"Aprobado",                             "titulo":"APR"},
            {"campo":"estadoAnulado",                  "tipo":"bool",  "ayuda":"Anulado",                              "titulo":"ANU"}
        ]';
        return $campos;
    }

    public function getEstructuraPropiedadesFiltro()
    {
        $campos = '[
            {"child":"codigoCuentaPagarPk", "tipo":"TextType",  "propiedades":{"label":"Codigo"}},
            {"child":"codigoTerceroFk",   "tipo":"TextType",  "propiedades":{"label":"Codigo proveedor"}},
            {"child":"numeroDocumento",              "tipo":"TextType",  "propiedades":{"label":"Numero"}},
            {"child":"fechaFacturaDesde",          "tipo":"DateType",  "propiedades":{"label":"Fecha Desde"}},
            {"child":"fechaFacturaHasta",          "tipo":"DateType",  "propiedades":{"label":"Fecha Hasta"}},
            {"child":"estadoAutorizado",    "tipo":"ChoiceType","propiedades":{"label":"Autorizado",     "choices":{"SI":true,"NO":false}}},
            {"child":"estadoAprobado",      "tipo":"ChoiceType","propiedades":{"label":"Aprobado",       "choices":{"SI":true,"NO":false}}},
            {"child":"estadoAnulado",       "tipo":"ChoiceType","propiedades":{"label":"Anulado",        "choices":{"SI":true,"NO":false}}}
        ]';

        return $campos;
    }
}
