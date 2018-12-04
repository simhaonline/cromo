<?php

namespace App\Form\Type\RecursoHumano;

use App\Entity\RecursoHumano\RhuRecaudo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecaudoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fecha')
            ->add('codigoEntidadSaludFk')
            ->add('numero')
            ->add('comentarios')
            ->add('vrTotal')
            ->add('estadoAutorizado')
            ->add('fechaPago')
            ->add('estadoAprobado')
            ->add('estadoAnulado')
            ->add('reciboCaja')
            ->add('ValorReciboCaja')
            ->add('estadoCerrado')
            ->add('vrTotalEntidad')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RhuRecaudo::class,
        ]);
    }

    public function getEstructuraPropiedadesLista()
    {
        $campos = '[
            {"campo":"codigoRecaudoPk",   "tipo":"pk"     ,"ayuda":"Codigo del registro" ,"titulo":"ID"},
            {"campo":"numero",            "tipo":"texto"  ,"ayuda":"Numero"              ,"titulo":"NUMERO"},
            {"campo":"fecha",             "tipo":"fecha"  ,"ayuda":"Fecha del registro"  ,"titulo":"FECHA"},
            {"campo":"codigoEntidadFk",   "tipo":"texto"  ,"ayuda":"Codigo de la entidad","titulo":"ENTIDAD"},
            {"campo":"fechaPago",         "tipo":"fecha"  ,"ayuda":"Fecha pago"          ,"titulo":"F.PAGO"},                     
            {"campo":"vrTotal",           "tipo":"moneda" ,"ayuda":"Valor total"         ,"titulo":"TOTAL"},
            {"campo":"comentarios",       "tipo":"text"   ,"ayuda":"Comentarios"         ,"titulo":"COMENTARIOS"},                     
            {"campo":"estadoAutorizado",  "tipo":"bool"   ,"ayuda":"Estado autorizado"   ,"titulo":"AUT"},                     
            {"campo":"estadoAprobado",    "tipo":"bool"   ,"ayuda":"Estado aprobado"     ,"titulo":"APR"},                     
            {"campo":"estadoAnulado",     "tipo":"bool"   ,"ayuda":"Estado anulado"      ,"titulo":"ANU"}
        ]';
        return $campos;
    }

    public function getEstructuraPropiedadesFiltro()
    {
        $campos = '[
            {"child":"codigoRecaudoPk",  "tipo":"TextType",   "propiedades":{"label":"Codigo"}},
            {"child":"codigoEntidadFk",  "tipo":"EntityType", "propiedades":{"class":"RhuGrupo","choice_label":"nombre","label":"Grupo"}},
            {"child":"numero",           "tipo":"TextType",   "propiedades":{"label":"Numero"}},
            {"child":"fechaPagoDesde",   "tipo":"DateType",   "propiedades":{"label":"Fecha Desde"}},
            {"child":"fechaPagoHasta",   "tipo":"DateType",   "propiedades":{"label":"Fecha Hasta"}},
            {"child":"estadoAutorizado", "tipo":"ChoiceType", "propiedades":{"label":"Autorizado",     "choices":{"SI":true,"NO":false}}},
            {"child":"estadoAprobado",   "tipo":"ChoiceType", "propiedades":{"label":"Aprobado",       "choices":{"SI":true,"NO":false}}},
            {"child":"estadoAnulado",    "tipo":"ChoiceType", "propiedades":{"label":"Anulado",        "choices":{"SI":true,"NO":false}}}
        ]';

        return $campos;
    }
}
