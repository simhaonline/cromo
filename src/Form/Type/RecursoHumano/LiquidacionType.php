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
            {"campo":"fecha",             "tipo":"fecha" ,"ayuda":"Fecha del registro"    ,"titulo":"FECHA"},
            {"campo":"empleadoRel.nombreCorto",  "tipo":"texto"   ,"ayuda":"Nombre del empleado"        ,"titulo":"EMPLEADO", "relacion":""},
            {"campo":"codigoGrupoFk",     "tipo":"texto" ,"ayuda":"Codigo del grupo"      ,"titulo":"GRUPO"},
            {"campo":"fechaDesde",        "tipo":"fecha" ,"ayuda":"Fecha desde"           ,"titulo":"DESDE"},                     
            {"campo":"fechaHasta",        "tipo":"fecha" ,"ayuda":"Fecha hasta"           ,"titulo":"HASTA"},                     
            {"campo":"estadoAutorizado",  "tipo":"bool"  ,"ayuda":"Estado autorizado"     ,"titulo":"AUT"},                     
            {"campo":"estadoAprobado",    "tipo":"bool"  ,"ayuda":"Estado aprobado"       ,"titulo":"APR"},                     
            {"campo":"estadoAnulado",     "tipo":"bool"  ,"ayuda":"Estado anulado"        ,"titulo":"ANU"}                                          
        ]';
        return $campos;
    }

    public function getEstructuraPropiedadesFiltro()
    {
        $campos = '[
            {"child":"codigoLiquidacionPk", "tipo":"TextType",   "propiedades":{"label":"Codigo"}},
            {"child":"codigoGrupoFk",       "tipo":"EntityType", "propiedades":{"class":"RhuGrupo","choice_label":"nombre","label":"Grupo"}},
            {"child":"numero",              "tipo":"TextType",   "propiedades":{"label":"Numero"}},
            {"child":"codigoEmpleadoFk",    "tipo":"TextType",   "propiedades":{"label":"Empleado"}},
            {"child":"fechaDesde",          "tipo":"DateType",   "propiedades":{"label":"Fecha Desde"}},
            {"child":"fechaHasta",          "tipo":"DateType",   "propiedades":{"label":"Fecha Hasta"}},
            {"child":"estadoAutorizado",    "tipo":"ChoiceType", "propiedades":{"label":"Autorizado",     "choices":{"SI":true,"NO":false}}},
            {"child":"estadoAprobado",      "tipo":"ChoiceType", "propiedades":{"label":"Aprobado",       "choices":{"SI":true,"NO":false}}},
            {"child":"estadoAnulado",       "tipo":"ChoiceType", "propiedades":{"label":"Anulado",        "choices":{"SI":true,"NO":false}}}
        ]';

        return $campos;
    }
}
