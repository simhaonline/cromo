<?php

namespace App\Form\Type\RecursoHumano;

use App\Entity\RecursoHumano\RhuVacacion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VacacionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fechaDesdeDisfrute', DateType::class, array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('fechaHastaDisfrute', DateType::class, array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('fechaInicioLabor', DateType::class, array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('diasDisfrutados',TextType::class,['required' => true])
            ->add('diasPagados',TextType::class,['required' => true])
            ->add('comentarios',TextareaType::class,['required' => false])
            ->add('vrSalarioPromedioPropuesto',TextType::class,['required' => true])
            ->add('vrDisfrutePropuesto',TextType::class,['required' => true])
            ->add('vrSalarioPromedioPropuestoPagado',TextType::class,['required' => true])
            ->add('vrSaludPropuesto',TextType::class,['required' => true])
            ->add('vrPensionPropuesto',TextType::class,['required' => true])
            ->add('guardar',SubmitType::class,['attr' => ['class' => 'btn btn-sm btn-primary']])
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
            {"campo":"numero",                "tipo":"texto"  ,"ayuda":"Consecutivo del registro","titulo":"NUMERO"},
            {"campo":"fecha",                 "tipo":"fecha"  ,"ayuda":"Fecha de registro","titulo":"FECHA"},
            {"campo":"grupoRel.nombre",       "tipo":"texto"  ,"ayuda":"Nombre del grupo al cual pertence el mepleado" , "titulo":"GRUPO", "relacion":""},
            {"campo":"empleadoRel.numeroIdentificacion","tipo":"texto"  ,"ayuda":"Codigo del empleado",    "titulo":"IDENTIFICACION", "relacion":""},
            {"campo":"empleadoRel.nombreCorto","tipo":"texto"  ,"ayuda":"Codigo del empleado",       "titulo":"EMPLEADO", "relacion":""},
            {"campo":"fechaDesdePeriodo",     "tipo":"fecha"  ,"ayuda":"Fecha desde periodo",        "titulo":"P.DESDE"},                     
            {"campo":"fechaHastaPeriodo",     "tipo":"fecha"  ,"ayuda":"Fecha hasta periodo",        "titulo":"P.HASTA"},                     
            {"campo":"fechaDesdeDisfrute",    "tipo":"fecha"  ,"ayuda":"Fecha desde disfrute",       "titulo":"DESDE"},                     
            {"campo":"fechaHastaDisfrute",    "tipo":"fecha"  ,"ayuda":"Fecha desde disfrute",       "titulo":"HASTA"},
            {"campo":"fechaInicioLabor",      "tipo":"fecha"  ,"ayuda":"Fecha inicio labores",       "titulo":"INICIO"},
            {"campo":"diasPagados",           "tipo":"texto"  ,"ayuda":"Dias pagados",               "titulo":"D.P"},
            {"campo":"diasDisfrutados",       "tipo":"texto"  ,"ayuda":"Dias disfrutados" ,          "titulo":"D.D"},                     
            {"campo":"diasDisfrutadosReales", "tipo":"texto"  ,"ayuda":"Dias disfrutados reales",    "titulo":"D.D.R"},                                        
            {"campo":"vrTotal",               "tipo":"moneda" ,"ayuda":"Valor total de la vacacion", "titulo":"TOTAL"},                                        
            {"campo":"estadoAutorizado",      "tipo":"bool"   ,"ayuda":"Estado autorizado", "titulo":"AUT"},                                        
            {"campo":"estadoPagado",          "tipo":"bool"   ,"ayuda":"Estado pagado",     "titulo":"PAG"},                                        
            {"campo":"estadoAnulado",         "tipo":"bool"   ,"ayuda":"Estado anulado",    "titulo":"ANU"}                                        
        ]';
        return $campos;
    }

    public function getEstructuraPropiedadesFiltro()
    {
        $campos = '[
            {"child":"codigoVacacionPk", "tipo":"TextType",   "propiedades":{"label":"Codigo"}},
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
