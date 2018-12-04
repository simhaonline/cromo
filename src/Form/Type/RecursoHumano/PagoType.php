<?php

namespace App\Form\Type\RecursoHumano;

use App\Entity\RecursoHumano\RhuPago;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PagoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoPagoTipoFk')
            ->add('codigoPeriodoFk')
            ->add('numero')
            ->add('codigoEmpleadoFk')
            ->add('codigoContratoFk')
            ->add('codigoGrupoFk')
            ->add('codigoProgramacionFk')
            ->add('codigoProgramacionDetalleFk')
            ->add('codigoVacacionFk')
            ->add('codigoLiquidacionFk')
            ->add('codigoEntidadSaludFk')
            ->add('codigoEntidadPensionFk')
            ->add('fechaDesde')
            ->add('fechaHasta')
            ->add('fechaDesdeContrato')
            ->add('fechaHastaContrato')
            ->add('vrSalarioContrato')
            ->add('vrDevengado')
            ->add('vrDeduccion')
            ->add('vrAuxilioTransporte')
            ->add('vrIngresoBaseCotizacion')
            ->add('vrIngresoBasePrestacion')
            ->add('estadoAutorizado')
            ->add('estadoAprobado')
            ->add('estadoAnulado')
            ->add('comentario')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RhuPago::class,
        ]);
    }

    public function getEstructuraPropiedadesLista()
    {
        $campos = '[
            {"campo":"codigoPagoPk",      "tipo":"pk"      ,"ayuda":"Codigo del registro"                    ,"titulo":"ID"},
            {"campo":"numero",            "tipo":"texto"   ,"ayuda":"Numero"                       ,"titulo":"NUMERO"},
            {"campo":"pagoTipoRel.nombre",       "tipo":"texto"   ,"ayuda":"Tipo de pago"                ,"titulo":"TIPO", "relacion":""},
            {"campo":"empleadoRel.numeroIdentificacion",  "tipo":"texto"   ,"ayuda":"Numero de identificacion del empleado"        ,"titulo":"IDENTIFICACION", "relacion":""},
            {"campo":"empleadoRel.nombreCorto",  "tipo":"texto"   ,"ayuda":"Nombre del empleado"        ,"titulo":"EMPLEADO", "relacion":""},
            {"campo":"fechaDesde",        "tipo":"fecha"   ,"ayuda":"Fecha desde" ,"titulo":"DESDE"},
            {"campo":"fechaHasta",        "tipo":"fecha"   ,"ayuda":"Fecha hasta" ,"titulo":"HASTA"},
            {"campo":"vrSalarioContrato", "tipo":"moneda"   ,"ayuda":"Salario del empleado al momento de realizado el pago" ,"titulo":"SALARIO"},                     
            {"campo":"vrDevengado",       "tipo":"moneda"   ,"ayuda":"Valor devengado" ,"titulo":"DEVENGADO"},
            {"campo":"vrDeduccion",       "tipo":"moneda"   ,"ayuda":"Valor de las deducciones" ,"titulo":"DEDUCCIONES"},
            {"campo":"vrNeto",            "tipo":"moneda"   ,"ayuda":"Valor neto del pago" ,"titulo":"NETO"}
        ]';
        return $campos;
    }

    public function getEstructuraPropiedadesFiltro()
    {
        $campos = '[
            {"child":"codigoEmpleadoFk",     "tipo":"TextType",  "propiedades":{"label":"Empleado"}},
            {"child":"codigoPagoPk", "tipo": "TextType",  "propiedades":{"label":"Codigo"}},
            {"child":"codigoPagoTipoFk",     "tipo":"EntityType","propiedades":{"class":"RhuPagoTipo","choice_label":"nombre","label":"Tipo"}},
            {"child":"numero",               "tipo":"TextType",  "propiedades":{"label":"Numero"}},
            {"child":"fechaDesdeDesde",      "tipo":"DateType",  "propiedades":{"label":"Fecha Desde"}},
            {"child":"fechaHastaHasta",      "tipo":"DateType",  "propiedades":{"label":"Fecha Hasta"}}
        ]';
        return $campos;
    }
}
