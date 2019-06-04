<?php

namespace App\Form\Type\RecursoHumano;

use App\Entity\RecursoHumano\RhuExamen;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExamenType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('examenClaseRel', EntityType::class, array(
                'required' => true,
                'class' => 'App\Entity\RecursoHumano\RhuExamenClase',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('ec')
                        ->orderBy('ec.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => ' '
            ))
            ->add('entidadExamenRel', EntityType::class, array(
                'required' => true,
                'class' => 'App\Entity\RecursoHumano\RhuEntidadExamen',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('ee')
                        ->orderBy('ee.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => ' '
            ))
            ->add('fecha', DateType::class, array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('comentario', TextareaType::class, array('required' => false))
            ->add('guardar', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RhuExamen::class,
        ]);
    }

    public function getEstructuraPropiedadesLista()
    {
        $campos = '[
            {"campo":"codigoExamenPk",       "tipo":"pk"      ,"ayuda":"Codigo del registro"                ,"titulo":"ID"},
            {"campo":"examenClaseRel.nombre","tipo":"texto"   ,"ayuda":"Tipo de Examen"                     ,"titulo":"TIPO", "relacion":""},
            {"campo":"fecha",                "tipo":"fecha",   "ayuda":"Fecha",                              "titulo":"FECHA"},
            {"campo":"numeroIdentificacion", "tipo":"texto",   "ayuda":"Numero documento",                   "titulo":"DOCUMENTO"},
            {"campo":"nombreCorto",          "tipo":"texto",   "ayuda":"Nombre empleado",                    "titulo":"NOMBRE"},
            {"campo":"entidadExamenRel.nombre","tipo":"texto", "ayuda":"Nombre",                             "titulo":"ENTIDAD", "default":"SIN ENTIDAD", "relacion":""},
            {"campo":"cargoRel.nombre",       "tipo":"texto",  "ayuda":"Nombre del cargo",                   "titulo":"CARGO", "relacion":""},
            {"campo":"cobro",                "tipo":"texto",   "ayuda":"Cobro",                              "titulo":"C"},
            {"campo":"vrTotal",              "tipo":"texto",   "ayuda":"",                                   "titulo":"TOTAL"},
            {"campo":"estadoAutorizado",     "tipo":"bool",   "ayuda":"Autorizado",                         "titulo":"AUT"},
            {"campo":"estadoAprobado",       "tipo":"bool",   "ayuda":"Aprobado",                           "titulo":"APR"},
            {"campo":"estadoAnulado",         "tipo":"bool",  "ayuda":"Anulado",                            "titulo":"ANU"}
        ]';
        return $campos;
    }

    public function getEstructuraPropiedadesFiltro()
    {
        $campos = '[
            {"child":"codigoExamenPk",     "tipo":"TextType",  "propiedades":{"label":"Codigo"}},
            {"child":"codigoExamenClaseFk", "tipo":"EntityType","propiedades":{"class":"RhuExamenClase","choice_label":"nombre", "label":"TODOS"}},
            {"child":"codigoEmpleadoFk",         "tipo":"TextType",    "propiedades":{"label":"Codigo Empleado"}},
            {"child":"fechaDesde",                      "tipo":"DateType",      "propiedades":{"label":"Fecha Desde"}},
            {"child":"fechaHasta",                      "tipo":"DateType",      "propiedades":{"label":"Fecha Hasta"}},        
            {"child":"estadoAutorizado",   "tipo":"ChoiceType","propiedades":{"label":"Autorizado",     "choices":{"SI":true,"NO":false}}},
            {"child":"estadoAprobado",      "tipo":"ChoiceType","propiedades":{"label":"Aprobado",      "choices":{"SI":true,"NO":false}}},
            {"child":"estadoAnulado",      "tipo":"ChoiceType","propiedades":{"label":"Anulado",      "choices":{"SI":true,"NO":false}}}
        ]';
        return $campos;
    }
}
