<?php

namespace App\Form\Type\Inventario;

use App\Entity\General\GenMoneda;
use App\Entity\Inventario\InvCosto;
use App\Entity\Inventario\InvCostoTipo;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CostoType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('costoTipoRel',EntityType::class,[
                'required' => true,
                'class' => InvCostoTipo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('it')
                        ->orderBy('it.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Costo tipo:'
            ])
            ->add('anio',NumberType::class, ['required' => false,'label' => 'AÑO:'])
            ->add('mes',NumberType::class, ['required' => false,'label' => 'MES:'])
            ->add('comentario',TextareaType::class, ['required' => false,'label' => 'Comentario:'])
            ->add('guardar', SubmitType::class, ['label'=>'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\Inventario\InvCosto'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'App_costo';
    }

    public function getEstructuraPropiedadesLista()
    {
        $campos = '[
            {"campo":"codigoCostoPk",             "tipo":"pk",        "ayuda":"Codigo del registro",                  "titulo":"ID"},
            {"campo":"costoTipoRel.nombre",       "tipo":"texto",     "ayuda":"Tipo de costo",                  "titulo":"COSTO TIPO",         "relacion":""},
            {"campo":"anio",                          "tipo":"entero",     "ayuda":"Anio",                  "titulo":"ANIO"},
            {"campo":"mes",                          "tipo":"entero",     "ayuda":"Mes",                  "titulo":"MES"},
            {"campo":"vrCosto",                    "tipo":"moneda",    "ayuda":"Total",                          "titulo":"TOTAL"},
            {"campo":"estadoAutorizado",                "tipo":"bool",      "ayuda":"Autorizdo",                            "titulo":"AUT"},
            {"campo":"estadoAprobado",                  "tipo":"bool",      "ayuda":"Aprobado",                             "titulo":"APR"},
            {"campo":"estadoAnulado",                   "tipo":"bool",      "ayuda":"Anulado",                              "titulo":"ANU"}
                                     
        ]';
        return $campos;
    }

    public function getEstructuraPropiedadesFiltro()
    {
        $campos = '[            
            {"child":"codigoCostoPk",     "tipo":"TextType",    "propiedades":{"label":"Codigo"}},            
            {"child":"codigoCostoTipoFk", "tipo":"EntityType",  "propiedades":{"class":"InvCostoTipo","choice_label":"nombre","label":"Tipo"}},
            {"child":"estadoAutorizado",        "tipo":"ChoiceType",  "propiedades":{"label":"Autorizado",    "choices":{"SI":true,"NO":false}}},
            {"child":"estadoAprobado",          "tipo":"ChoiceType",  "propiedades":{"label":"Aprobado",    "choices":{"SI":true,"NO":false}}},
            {"child":"estadoAnulado",           "tipo":"ChoiceType",  "propiedades":{"label":"Anulado",     "choices":{"SI":true,"NO":false}}}
        ]';
        return $campos;
    }

}
