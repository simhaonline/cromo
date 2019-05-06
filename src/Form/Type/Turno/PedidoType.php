<?php

namespace App\Form\Type\Turno;

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

class PedidoType extends AbstractType
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
            ->add('pedidoTipoRel', EntityType::class, [
                'required' => true,
                'class' => 'App\Entity\Turno\TurPedidoTipo',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('pt')
                        ->orderBy('pt.codigoPedidoTipoPk', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Tipo pedido:'
            ])
        ->add('fechaProgramacion', DateType::class, ['required' => true, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => ['class' => 'date',]])
        ->add('estrato', NumberType::class, array('required'=>false))
        ->add('vrSalarioBase', NumberType::class)
        ->add('comentario', TextareaType::class, array('required' => false))
        ->add('soporte', TextType::class, array('required' => false))
        ->add('guardar', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TurPedido::class,
        ]);
    }

    public function getEstructuraPropiedadesLista()
    {
        $campos = '[
            {"campo":"codigoPedidoPk",               "tipo":"pk",    "ayuda":"Codigo del registro",                  "titulo":"ID"},
            {"campo":"numero",                         "tipo":"texto", "ayuda":"Consecutivo de aprobación",            "titulo":"NUMERO"},
            {"campo":"pedidoTipoRel.nombre",         "tipo":"texto", "ayuda":"Tipo pedido",                        "titulo":"TIPO",          "relacion":""},
            {"campo":"fecha",                          "tipo":"fecha", "ayuda":"Fecha",                                "titulo":"FECHA"},
            {"campo":"clienteRel.numeroIdentificacion","tipo":"texto", "ayuda":"Numero de identificacion del cliente", "titulo":"IDENTIFICACION","relacion":""},
            {"campo":"clienteRel.nombreCorto",         "tipo":"texto", "ayuda":"Nombre del cliente",                   "titulo":"NOMBRE",        "relacion":""},
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
            {"child":"codigoPedidoPk",     "tipo":"TextType",  "propiedades":{"label":"Codigo"}},
            {"child":"numero",             "tipo":"TextType",  "propiedades":{"label":"Numero"}},
            {"child":"codigoPedidoTipoFk", "tipo":"EntityType","propiedades":{"class":"TurPedidoTipo","choice_label":"nombre", "label":"TODOS"}},
            {"child":"fechaDesde",         "tipo":"DateType",  "propiedades":{"label":"Fecha Desde"}},
            {"child":"fechaHasta",         "tipo":"DateType",  "propiedades":{"label":"Fecha Hasta"}},
            {"child":"estadoAutorizado",   "tipo":"ChoiceType","propiedades":{"label":"Autorizado",     "choices":{"SI":true,"NO":false}}},
            {"child":"estadoAprobado",     "tipo":"ChoiceType","propiedades":{"label":"Aprobado",       "choices":{"SI":true,"NO":false}}},
            {"child":"estadoAnulado",      "tipo":"ChoiceType","propiedades":{"label":"Anulado",        "choices":{"SI":true,"NO":false}}}
        ]';

        return $campos;
    }
}
