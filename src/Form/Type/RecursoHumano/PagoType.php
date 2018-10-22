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
            {"campo":"codigoPagoTipoFk",  "tipo":"texto"   ,"ayuda":"Tipo de pago"         ,"titulo":"TIPO"},
            {"campo":"codigoEmpleadoFk",  "tipo":"texto"   ,"ayuda":"Codigo del empleado"        ,"titulo":"EMPLEADO"},
            {"campo":"codigoGrupoFk",     "tipo":"texto"   ,"ayuda":"Codigo del grupo"              ,"titulo":"GRUPO"},
            {"campo":"fechaDesde",        "tipo":"fecha"   ,"ayuda":"Fecha desde" ,"titulo":"DESDE"},                     
            {"campo":"fechaHasta",        "tipo":"fecha"   ,"ayuda":"Fecha hasta" ,"titulo":"HASTA"},                     
            {"campo":"vrSalarioContrato", "tipo":"moneda"   ,"ayuda":"Salario del empleado al momento de realizado el pago" ,"titulo":"SAL"},                     
            {"campo":"vrDevengado",       "tipo":"moneda"   ,"ayuda":"Valor devengado" ,"titulo":"DEV"},                     
            {"campo":"vrDeduccion",       "tipo":"moneda"   ,"ayuda":"Valor de las deducciones" ,"titulo":"DED"}                                          
        ]';
        return $campos;
    }
}
