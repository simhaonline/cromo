<?php

namespace App\Form\Type\RecursoHumano;

use App\Entity\RecursoHumano\RhuVacacion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VacacionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoEmpleadoFk')
            ->add('codigoContratoFk')
            ->add('fecha')
            ->add('fechaContabilidad')
            ->add('numero')
            ->add('fechaDesdePeriodo')
            ->add('fechaHastaPeriodo')
            ->add('fechaDesdeDisfrute')
            ->add('fechaHastaDisfrute')
            ->add('fechaInicioLabor')
            ->add('vrSalud')
            ->add('vrPension')
            ->add('vrFondoSolidaridad')
            ->add('vrIbc')
            ->add('vrDeduccion')
            ->add('vrBonificacion')
            ->add('vrVacacion')
            ->add('vrVacacionDisfrute')
            ->add('vrVacacionDinero')
            ->add('vrVacacionTotal')
            ->add('diasVacaciones')
            ->add('diasDisfrutados')
            ->add('diasAusentismo')
            ->add('diasPagados')
            ->add('diasDisfrutadosReales')
            ->add('diasPeriodo')
            ->add('mesesPeriodo')
            ->add('comentarios')
            ->add('codigoCentroCostoFk')
            ->add('vrSalarioActual')
            ->add('vrSalarioPromedio')
            ->add('vrSalarioPromedioPropuesto')
            ->add('vrVacacionDisfrutePropuesto')
            ->add('vrSalarioPromedioPropuestoPagado')
            ->add('vrSaludPropuesto')
            ->add('vrPensionPropuesto')
            ->add('diasAusentismoPropuesto')
            ->add('vrVacacionBruto')
            ->add('estadoPagoGenerado')
            ->add('estadoPagoBanco')
            ->add('estadoContabilizado')
            ->add('estadoAutorizado')
            ->add('estadoAprobado')
            ->add('estadoAnulado')
            ->add('estadoPagado')
            ->add('estadoLiquidado')
            ->add('usuario')
            ->add('vrRecargoNocturnoInicial')
            ->add('vrRecargoNocturno')
            ->add('vrPromedioRecargoNocturno')
            ->add('vrIbcPromedio')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RhuVacacion::class,
        ]);
    }

    public function getEstructuraPropiedadesLista()
    {
        $campos = '[
            {"campo":"codigoVacacionPk",      "tipo":"pk"     ,"ayuda":"Codigo del registro",     "titulo":"ID"},
            {"campo":"codigoEmpleadoFk",      "tipo":"texto"  ,"ayuda":"Codigo del empleado",     "titulo":"EMPLEADO"},
            {"campo":"fechaDesdePeriodo",     "tipo":"fecha"  ,"ayuda":"Fecha desde periodo",     "titulo":"P.DESDE"},                     
            {"campo":"fechaHastaPeriodo",     "tipo":"fecha"  ,"ayuda":"Fecha hasta periodo",     "titulo":"P.HASTA"},                     
            {"campo":"fechaDesdeDisfrute",    "tipo":"fecha"  ,"ayuda":"Fecha desde disfrute",    "titulo":"D.DESDE"},                     
            {"campo":"fechaHastaDisfrute",    "tipo":"fecha"  ,"ayuda":"Fecha desde disfrute",    "titulo":"D.DESDE"},
            {"campo":"diasPagados",           "tipo":"fecha"  ,"ayuda":"Dias pagados",            "titulo":"D.P"},
            {"campo":"diasDisfrutados",       "tipo":"texto"  ,"ayuda":"Dias disfrutados" ,       "titulo":"D.D"},                     
            {"campo":"diasDisfrutadosReales", "tipo":"moneda" ,"ayuda":"Dias disfrutados reales", "titulo":"D.D.R"}                                          
        ]';
        return $campos;
    }
}
