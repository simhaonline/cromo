<?php

namespace App\Form\Type\Inventario;

use App\Entity\Inventario\InvServicioTipo;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ServicioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('servicioTipoRel', EntityType::class, array(
                'class' => InvServicioTipo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('st')
                        ->orderBy('st.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
            ))
            ->add('comentario',TextareaType::class, array('required' => false))
            ->add('guardar', SubmitType::class,array('label'=>'Guardar'));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'App\Entity\Inventario\InvServicio'
        ]);
    }

    public function getEstructuraPropiedadesLista()
    {
        $campos = '[
            {"campo":"codigoServicioPk",                        "tipo":"pk",        "ayuda":"Codigo servicio",                          "titulo":"ID"},
            {"campo":"servicioTipoRel.nombre",                  "tipo":"texto",     "ayuda":"Tipo Servicio",                            "titulo":"TIPO",                    "relacion":""},
            {"campo":"fecha",                                   "tipo":"fecha",     "ayuda":"Fecha",                                    "titulo":"FECHA"},
            {"campo":"comentario",                              "tipo":"texto",     "ayuda":"Comentario",                               "titulo":"COMENTARIO"},
            {"campo":"estadoAutorizado",                        "tipo":"bool",      "ayuda":"Autorizado",                               "titulo":"AUT"},
            {"campo":"estadoAprobado",                          "tipo":"bool",      "ayuda":"Aprobado",                                 "titulo":"APR"},
            {"campo":"estadoAnulado",                           "tipo":"bool",      "ayuda":"Anulado",                                  "titulo":"ANU"}
        ]';
        return $campos;

    }


    public function getEstructuraPropiedadesFiltro()
    {

        $campos = '[
            {"child":"codigoServicioPk",                "tipo":"TextType",      "propiedades":{"label":"Codigo"}},
            {"child":"codigoServicioTipoFk",            "tipo":"EntityType",    "propiedades":{"class":"InvServicioTipo",   "choice_label":"nombre","label":"TODOS"}},
            {"child":"estadoAutorizado",                "tipo":"ChoiceType",    "propiedades":{"label":"Autorizado",        "choices":{"SI":true,"NO":false}}},
            {"child":"estadoAprobado",                  "tipo":"ChoiceType",    "propiedades":{"label":"Aprobado",          "choices":{"SI":true,"NO":false}}},
            {"child":"estadoAnulado",                   "tipo":"ChoiceType",    "propiedades":{"label":"Anulado",           "choices":{"SI":true,"NO":false}}},
            {"child":"fechaDesde",                      "tipo":"DateType",      "propiedades":{"label":"Fecha Desde"}},
            {"child":"fechaHasta",                      "tipo":"DateType",      "propiedades":{"label":"Fecha Hasta"}}
        ]';

        return $campos;
    }
}
