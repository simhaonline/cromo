<?php

namespace App\Form\Type\Turno;

use App\Entity\Turno\TurCotizacion;
use App\Entity\Turno\TurPedido;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CotizacionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('sectorRel', EntityType::class, [
                'required' => true,
                'class' => 'App\Entity\Turno\TurSector',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('s')
                        ->orderBy('s.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'nombre:'
            ])
            ->add('cotizacionTipoRel', EntityType::class, [
                'required' => true,
                'class' => 'App\Entity\Turno\TurCotizacionTipo',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('ct')
                        ->orderBy('ct.codigoCotizacionTipoPk', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Tipo pedido:'
            ])
            ->add('estrato', NumberType::class, array('required' => false))
            ->add('vrSalarioBase', NumberType::class)
            ->add('comentario', TextareaType::class, array('required' => false))
            ->add('guardar', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TurCotizacion::class,
        ]);
    }

    public function getEstructuraPropiedadesLista()
    {
        $campos = '[
            {"campo":"codigoCotizacionPk",               "tipo":"pk",    "ayuda":"Codigo del registro",                  "titulo":"ID"},
            {"campo":"cotizacionTipoRel.nombre",         "tipo":"texto", "ayuda":"Tipo cotizacion",                        "titulo":"TIPO",          "relacion":""},
            {"campo":"numero",                         "tipo":"texto", "ayuda":"Consecutivo de aprobación",            "titulo":"NUMERO"},
            {"campo":"fecha",                          "tipo":"fecha", "ayuda":"Fecha",                                "titulo":"FECHA"},            
            {"campo":"clienteRel.nombreCorto",         "tipo":"texto", "ayuda":"Nombre del cliente",                   "titulo":"CLIENTE",        "relacion":""},
            {"campo":"sectorRel.nombre",         "tipo":"texto", "ayuda":"Nombre del sector",                   "titulo":"SECTOR",        "relacion":""},
            {"campo":"horas",                         "tipo":"texto", "ayuda":"Horas",            "titulo":"H"},
            {"campo":"horasDiurnas",                         "tipo":"texto", "ayuda":"Horas diurnas",            "titulo":"HD"},
            {"campo":"horasNocturnas",                         "tipo":"texto", "ayuda":"Horas nocturnas",            "titulo":"HN"},
            {"campo":"vrTotal",                         "tipo":"moneda", "ayuda":"Total",            "titulo":"TOTAL"},
            {"campo":"usuario",                        "tipo":"texto", "ayuda":"Usuario",                              "titulo":"USU"},            
            {"campo":"estadoAutorizado",               "tipo":"bool",  "ayuda":"Autorizado",                           "titulo":"AUT"},
            {"campo":"estadoAprobado",                 "tipo":"bool",  "ayuda":"Aprobado",                             "titulo":"APR"},
            {"campo":"estadoAnulado",                  "tipo":"bool",  "ayuda":"Anulado",                              "titulo":"ANU"}
        ]';
        return $campos;
    }

    public function getEstructuraPropiedadesFiltro()
    {

        $campos = '[
            {"child":"codigoCotizacionPk",     "tipo":"TextType",  "propiedades":{"label":"Codigo"}},
            {"child":"numero",     "tipo":"TextType",  "propiedades":{"label":"Numero"}},
            {"child":"codigoCotizacionTipoFk", "tipo":"EntityType","propiedades":{"class":"TurCotizacionTipo","choice_label":"nombre", "label":"TODOS"}},
            {"child":"estadoAutorizado",   "tipo":"ChoiceType","propiedades":{"label":"Autorizado",     "choices":{"SI":true,"NO":false}}},
            {"child":"estadoAprobado",   "tipo":"ChoiceType","propiedades":{"label":"Autorizado",     "choices":{"SI":true,"NO":false}}},
            {"child":"estadoAnulado",   "tipo":"ChoiceType","propiedades":{"label":"Autorizado",     "choices":{"SI":true,"NO":false}}}
        ]';

        return $campos;
    }
}
