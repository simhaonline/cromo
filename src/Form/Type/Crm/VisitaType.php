<?php

namespace App\Form\Type\Crm;

use App\Entity\Crm\CrmVisitaTipo;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VisitaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('visitaTipoRel', EntityType::class, array(
                'class' => CrmVisitaTipo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('st')
                        ->orderBy('st.codigoVisitaTipoPk', 'ASC');
                },
                'choice_label' => 'nombre',
            ))
            ->add('comentarios',TextareaType::class, array('required' => false))
            ->add('guardar', SubmitType::class,array('label'=>'Guardar'));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'App\Entity\Crm\CrmVisita'
        ]);
    }

    public function getEstructuraPropiedadesLista()
    {
        $campos = '[
            {"campo":"codigoVisitaPk",                          "tipo":"pk",        "ayuda":"Codigo de visita",                          "titulo":"ID"},
            {"campo":"visitaTipoRel.nombre",                    "tipo":"texto",     "ayuda":"Visita tipo",                               "titulo":"TIPO",                "relacion":""},
            {"campo":"fecha",                                   "tipo":"fecha",     "ayuda":"Fecha",                                    "titulo":"FECHA"},
            {"campo":"comentarios",                             "tipo":"texto",     "ayuda":"Comentarios",                              "titulo":"COMENTARIOS"},
            {"campo":"estadoAutorizado",                        "tipo":"bool",      "ayuda":"Autorizado",                               "titulo":"AUT"},
            {"campo":"estadoAprobado",                          "tipo":"bool",      "ayuda":"Aprobado",                                 "titulo":"APR"},
            {"campo":"estadoAnulado",                           "tipo":"bool",      "ayuda":"Anulado",                                  "titulo":"ANU"}
        ]';
        return $campos;

    }


    public function getEstructuraPropiedadesFiltro()
    {

        $campos = '[
            {"child":"codigoVisitaPk",                  "tipo":"TextType",      "propiedades":{"label":"Codigo"}},
            {"child":"codigoVisitaTipoFk",              "tipo":"EntityType",    "propiedades":{"class":"CrmVisitaTipo",   "choice_label":"nombre","label":"TODOS"}},
            {"child":"fechaDesde",                      "tipo":"DateType",      "propiedades":{"label":"Fecha Desde"}},
            {"child":"fechaHasta",                      "tipo":"DateType",      "propiedades":{"label":"Fecha Hasta"}},
            {"child":"estadoAutorizado",                "tipo":"ChoiceType",    "propiedades":{"label":"Autorizado",     "choices":{"SI":true,"NO":false}}},
            {"child":"estadoAprobado",                  "tipo":"ChoiceType",    "propiedades":{"label":"Aprobado",       "choices":{"SI":true,"NO":false}}},
            {"child":"estadoAnulado",                   "tipo":"ChoiceType",    "propiedades":{"label":"Anulado",        "choices":{"SI":true,"NO":false}}}
        ]';

        return $campos;
    }
}
