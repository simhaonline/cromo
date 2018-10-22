<?php

namespace App\Form\Type\RecursoHumano;

use App\Entity\RecursoHumano\RhuLiquidacion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LiquidacionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fecha')
            ->add('numero')
            ->add('codigoEmpleadoFk')
            ->add('codigoContratoFk')
            ->add('codigoGrupoFk')
            ->add('codigoContratoMotivoFk')
            ->add('fechaDesde')
            ->add('fechaHasta')
            ->add('vrCesantias')
            ->add('vrInteresesCesantias')
            ->add('vrPrima')
            ->add('vrVacacion')
            ->add('vrIndemnizacion')
            ->add('diasCesantias')
            ->add('diasCesantiasAusentismo')
            ->add('diasVacacion')
            ->add('diasVacacionAusentismo')
            ->add('diasPrima')
            ->add('diasPrimaAusentismo')
            ->add('fechaUltimoPagoPrima')
            ->add('fechaUltimoPagoVacacion')
            ->add('fechaUltimoPagoCesantias')
            ->add('estadoAutorizado')
            ->add('estadoAprobado')
            ->add('estadoAnulado')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RhuLiquidacion::class,
        ]);
    }

    public function getEstructuraPropiedadesLista()
    {
        $campos = '[
            {"campo":"codigoLiquidacionPk", "tipo":"pk"    ,"ayuda":"Codigo del registro" ,"titulo":"ID"},
            {"campo":"numero",            "tipo":"texto" ,"ayuda":"Numero"                ,"titulo":"NUMERO"},
            {"campo":"fecha",             "tipo":"fecha" ,"ayuda":"Fecha del registro"    ,"titulo":"TIPO"},
            {"campo":"codigoEmpleadoFk",  "tipo":"texto" ,"ayuda":"Codigo del empleado"   ,"titulo":"EMPLEADO"},
            {"campo":"codigoGrupoFk",     "tipo":"texto" ,"ayuda":"Codigo del grupo"      ,"titulo":"GRUPO"},
            {"campo":"fechaDesde",        "tipo":"fecha" ,"ayuda":"Fecha desde"           ,"titulo":"DESDE"},                     
            {"campo":"fechaHasta",        "tipo":"fecha" ,"ayuda":"Fecha hasta"           ,"titulo":"HASTA"},                     
            {"campo":"estadoAutorizado",  "tipo":"bool"  ,"ayuda":"Estado autorizado"     ,"titulo":"AUT"},                     
            {"campo":"estadoAprobado",    "tipo":"bool"  ,"ayuda":"Estado aprobado"       ,"titulo":"APR"},                     
            {"campo":"estadoAnulado",     "tipo":"bool"  ,"ayuda":"Estado anulado"        ,"titulo":"ANU"}                                          
        ]';
        return $campos;
    }
}
