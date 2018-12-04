<?php

namespace App\Form\Type\Inventario;

use App\Entity\Inventario\InvPedido;
use App\Entity\Inventario\InvPedidoTipo;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PedidoType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('pedidoTipoRel',EntityType::class,[
                'required' => true,
                'class' => InvPedidoTipo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('pt')
                        ->orderBy('pt.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Pedido tipo:'
            ])
            ->add('soporte',TextType::class, ['required' => false,'label' => 'Soporte:'])
            ->add('comentario',TextareaType::class, ['required' => false,'label' => 'Comentario:'])
            ->add('guardar', SubmitType::class, ['label'=>'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\Inventario\InvPedido'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'App_pedido';
    }

    public function getEstructuraPropiedadesLista()
    {
        $campos = '[
            {"campo":"codigoPedidoPk",               "tipo":"pk",        "ayuda":"Codigo del registro",                  "titulo":"ID"},
            {"campo":"pedidoTipoRel.nombre",         "tipo":"texto",     "ayuda":"Tipo de pedido",                       "titulo":"PEDIDO TIPO",         "relacion":""},
            {"campo":"terceroRel.nombreCorto",            "tipo":"texto",     "ayuda":"Tercero",                              "titulo":"TERCERO",         "relacion":""},
            {"campo":"numero",                       "tipo":"texto",     "ayuda":"Numero del registro",                  "titulo":"NUMERO"},
            {"campo":"fecha",                        "tipo":"fecha",     "ayuda":"Fecha del registro",                   "titulo":"FECHA"},
            {"campo":"vrSubtotal",                   "tipo":"moneda",    "ayuda":"Subtotal",                             "titulo":"SUBTOTAL"},
            {"campo":"vrIva",                        "tipo":"moneda",    "ayuda":"vrIva",                                "titulo":"IVA"},
            {"campo":"vrNeto",                       "tipo":"moneda",    "ayuda":"vrNeto",                               "titulo":"NETO"},
            {"campo":"vrTotal",                      "tipo":"moneda",    "ayuda":"Total",                                "titulo":"TOTAL"},
            {"campo":"estadoAutorizado",             "tipo":"bool",      "ayuda":"Autorizado",                            "titulo":"AUT"},
            {"campo":"estadoAprobado",               "tipo":"bool",      "ayuda":"Aprobado",                             "titulo":"APR"},
            {"campo":"estadoAnulado",                "tipo":"bool",      "ayuda":"Anulado",                              "titulo":"ANU"}
                                     
        ]';
        return $campos;
    }

    public function getEstructuraPropiedadesFiltro()
    {
        $campos = '[
            {"child":"numero",                "tipo":"TextType",    "propiedades":{"label":"Numero"}},
            {"child":"codigoPedidoPk",        "tipo":"TextType",    "propiedades":{"label":"Codigo"}},
            {"child":"codigoTerceroFk",       "tipo":"TextType",    "propiedades":{"label":"Codigo cliente"}},
            {"child":"codigoPedidoTipoFk",    "tipo":"EntityType",  "propiedades":{"class":"InvPedidoTipo","choice_label":"nombre","label":"Tipo"}},
            {"child":"estadoAutorizado",      "tipo":"ChoiceType",  "propiedades":{"label":"Autorizado",    "choices":{"SI":true,"NO":false}}},
            {"child":"estadoAprobado",        "tipo":"ChoiceType",  "propiedades":{"label":"Aprobado",    "choices":{"SI":true,"NO":false}}},
            {"child":"estadoAnulado",         "tipo":"ChoiceType",  "propiedades":{"label":"Anulado",     "choices":{"SI":true,"NO":false}}}
        ]';
        return $campos;
    }

}
