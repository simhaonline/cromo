<?php

namespace App\Form\Type\RecursoHumano;

use App\Entity\RecursoHumano\RhuNovedad;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NovedadType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoNovedadTipoFk')
            ->add('fecha')
            ->add('fechaDesde')
            ->add('fechaHasta')
            ->add('codigoEmpleadoFk')
            ->add('codigoContratoFk')
            ->add('cantidad')
            ->add('codigoCentroCostoFk')
            ->add('codigoEntidadSaludFk')
            ->add('comentarios')
            ->add('afectaTransporte')
            ->add('codigoUsuario')
            ->add('maternidad')
            ->add('paternidad')
            ->add('estadoCobrar')
            ->add('estadoCobrarCliente')
            ->add('estadoAutorizado')
            ->add('estadoAprobado')
            ->add('estadoAnulado')
            ->add('diasCobro')
            ->add('estadoProrroga')
            ->add('estadoTranscripcion')
            ->add('estadoLegalizado')
            ->add('pagarEmpleado')
            ->add('porcentajePago')
            ->add('vrCobro')
            ->add('vrLicencia')
            ->add('vrSaldo')
            ->add('vrPagado')
            ->add('vrIbcMesAnterior')
            ->add('diasIbcMesAnterior')
            ->add('vrHora')
            ->add('codigoNovedadProgramacion')
            ->add('aplicarAdicional')
            ->add('fechaAplicacion')
            ->add('vrAbono')
            ->add('vrIbcPropuesto')
            ->add('vrPropuesto')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RhuNovedad::class,
        ]);
    }

    public function getEstructuraPropiedadesLista()
    {
        $campos = '[
            {"campo":"codigoNovedadPk",    "tipo":"pk"    ,"ayuda":"Codigo del registro"       ,"titulo":"ID"},
            {"campo":"codigoNovedadTipoFk","tipo":"texto" ,"ayuda":"Codigo del tipo de novedad","titulo":"TIPO"},
            {"campo":"fecha",              "tipo":"fecha" ,"ayuda":"Fecha del registro"        ,"titulo":"FECHA"},
            {"campo":"codigoEmpleadoFk",   "tipo":"texto" ,"ayuda":"Codigo del empleado"       ,"titulo":"EMPLEADO"},
            {"campo":"codigoContratoFk",   "tipo":"texto" ,"ayuda":"Codigo del contrato"       ,"titulo":"CONTRATO"},
            {"campo":"fechaDesde",         "tipo":"fecha" ,"ayuda":"Fecha desde"               ,"titulo":"DESDE"},                     
            {"campo":"fechaHasta",         "tipo":"fecha" ,"ayuda":"Fecha hasta"               ,"titulo":"HASTA"}
        ]';
        return $campos;
    }
}
