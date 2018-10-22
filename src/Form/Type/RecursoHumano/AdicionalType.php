<?php

namespace App\Form\Type\RecursoHumano;

use App\Entity\RecursoHumano\RhuAdicional;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdicionalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoConceptoFk')
            ->add('codigoEmpleadoFk')
            ->add('codigoContratoFk')
            ->add('fecha')
            ->add('vrValor')
            ->add('permanente')
            ->add('aplicaDiaLaborado')
            ->add('aplicaPrima')
            ->add('aplicaCesantia')
            ->add('detalle')
            ->add('estadoInactivo')
            ->add('estadoInactivoPeriodo')
            ->add('estadoAutorizado')
            ->add('estadoAprobado')
            ->add('estadoAnulado')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RhuAdicional::class,
        ]);
    }

    public function getEstructuraPropiedadesLista()
    {
        $campos = '[
            {"campo":"codigoAdicionalPk","tipo":"pk"    ,"ayuda":"Codigo del registro" ,"titulo":"ID"},
            {"campo":"codigoConceptoFk", "tipo":"texto" ,"ayuda":"Codigo del concepto" ,"titulo":"CONCEPTO"},
            {"campo":"detalle",          "tipo":"fecha" ,"ayuda":"Detalle del registro","titulo":"DETALLE"},
            {"campo":"codigoEmpleadoFk", "tipo":"texto" ,"ayuda":"Codigo del empleado" ,"titulo":"EMPLEADO"},
            {"campo":"codigoContratoFk", "tipo":"texto" ,"ayuda":"Codigo del contrato" ,"titulo":"CONTRATO"},
            {"campo":"fecha",            "tipo":"fecha" ,"ayuda":"Fecha"               ,"titulo":"FECHA"},
            {"campo":"vrValor",          "tipo":"moneda","ayuda":"Valor del anticipo"  ,"titulo":"VALOR"},
            {"campo":"permanente",       "tipo":"bool"  ,"ayuda":"Permanente"          ,"titulo":"PER"},
            {"campo":"estadoAutorizado", "tipo":"bool"  ,"ayuda":"Estado autorizado"   ,"titulo":"AUT"},                     
            {"campo":"estadoAprobado",   "tipo":"bool"  ,"ayuda":"Estado aprobado"     ,"titulo":"APR"},                     
            {"campo":"estadoAnulado",    "tipo":"bool"  ,"ayuda":"Estado anulado"      ,"titulo":"ANU"}
        ]';
        return $campos;
    }
}
