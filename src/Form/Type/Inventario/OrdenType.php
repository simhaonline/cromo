<?php

namespace App\Form\Type\Inventario;

use App\Entity\Inventario\InvOrden;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrdenType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ordenTipoRel',EntityType::class,[
                'class' => 'App\Entity\Inventario\InvOrdenTipo',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('ot')
                        ->orderBy('ot.nombre','DESC');
                },
                'choice_label' => 'nombre',
                'label' => 'Tipo: '
            ])
            ->add('soporte',TextType::class,['required' => false])
            ->add('comentarios',TextareaType::class,['required' => false,'attr' => ['rows' => '5']])
            ->add('guardar',SubmitType::class,['label' => 'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']])
            ->add('guardarnuevo',SubmitType::class,['label' => 'Guardar y nuevo','attr' => ['class' => 'btn btn-sm btn-primary']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => InvOrden::class,
        ]);
    }

    public function getEstructuraPropiedadesLista()
    {
        $campos = '[
            {"campo":"codigoOrdenPk",                   "tipo":"pk",        "ayuda":"Codigo del registro",                  "titulo":"ID"},
            {"campo":"ordenTipoRel.nombre",             "tipo":"texto",     "ayuda":"Tipo de orden",                        "titulo":"ORDEN TIPO",         "relacion":""},
            {"campo":"numero",                          "tipo":"texto",     "ayuda":"Numero del registro",                  "titulo":"NUMERO"},
            {"campo":"fecha",                           "tipo":"fecha",     "ayuda":"Fecha del registro",                   "titulo":"FECHA"},
            {"campo":"fechaEntrega",                    "tipo":"fecha",     "ayuda":"Fecha de entrega del registro",        "titulo":"FECHA ENTREGA"},
            {"campo":"vrSubtotal",                      "tipo":"moneda",    "ayuda":"Subtotal",                             "titulo":"SUBTOTAL"},
            {"campo":"vrDescuento",                     "tipo":"moneda",    "ayuda":"vrDescuento",                          "titulo":"DESCUENTO"},
            {"campo":"vrIva",                           "tipo":"moneda",    "ayuda":"vrIva",                                "titulo":"IVA"},
            {"campo":"vrNeto",                          "tipo":"moneda",    "ayuda":"vrNeto",                               "titulo":"NETO"},
            {"campo":"vrTotal",                         "tipo":"moneda",    "ayuda":"Total",                                "titulo":"TOTAL"},
            {"campo":"estadoAutorizado",                "tipo":"bool",      "ayuda":"Autorizado",                            "titulo":"AUT"},
            {"campo":"estadoAprobado",                  "tipo":"bool",      "ayuda":"Aprobado",                             "titulo":"APR"},
            {"campo":"estadoAnulado",                   "tipo":"bool",      "ayuda":"Anulado",                              "titulo":"ANU"}
                                     
        ]';
        return $campos;
    }

    public function getEstructuraPropiedadesFiltro()
    {
        $campos = '[
            {"child":"numero",                  "tipo":"TextType",    "propiedades":{"label":"Numero"}},
            {"child":"codigoOrdenPk",           "tipo":"TextType",    "propiedades":{"label":"Codigo"}},
            {"child":"codigoTerceroFk",         "tipo":"TextType",    "propiedades":{"label":"Codigo cliente"}},
            {"child":"codigoOrdenTipoFk",       "tipo":"EntityType",  "propiedades":{"class":"InvOrdenTipo","choice_label":"nombre","label":"Tipo"}}
        ]';
        return $campos;
    }
}
