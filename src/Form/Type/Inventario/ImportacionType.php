<?php

namespace App\Form\Type\Inventario;

use App\Entity\General\GenMoneda;
use App\Entity\Inventario\InvImportacion;
use App\Entity\Inventario\InvImportacionTipo;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ImportacionType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('importacionTipoRel',EntityType::class,[
                'required' => true,
                'class' => InvImportacionTipo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('it')
                        ->orderBy('it.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Importacion tipo:'
            ])
            ->add('monedaRel',EntityType::class,[
                'required' => true,
                'class' => GenMoneda::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('m')
                        ->orderBy('m.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Moneda:'
            ])
            ->add('tasaRepresentativaMercado',NumberType::class, ['required' => false,'label' => 'TRM:'])
            ->add('soporte',TextType::class, ['required' => false,'label' => 'Soporte:'])
            ->add('comentario',TextareaType::class, ['required' => false,'label' => 'Comentario:'])
            ->add('guardar', SubmitType::class, ['label'=>'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\Inventario\InvImportacion'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'App_importacion';
    }

    public function getEstructuraPropiedadesLista()
    {
        $campos = '[
            {"campo":"codigoImportacionPk",             "tipo":"pk",        "ayuda":"Codigo del registro",                  "titulo":"ID"},
            {"campo":"importacionTipoRel.nombre",       "tipo":"texto",     "ayuda":"Tipo de importacion",                  "titulo":"IMPORTACION TIPO",         "relacion":""},
            {"campo":"numero",                          "tipo":"texto",     "ayuda":"Numero del registro",                  "titulo":"NUMERO"},
            {"campo":"fecha",                           "tipo":"fecha",     "ayuda":"Fecha del registro",                   "titulo":"FECHA"},
            {"campo":"monedaRel.nombre",                "tipo":"texto",     "ayuda":"Moneda en que se realizo el proceso",  "titulo":"MONEDA",                    "relacion":""},   
            {"campo":"tasaRepresentativaMercado",       "tipo":"moneda",    "ayuda":"TMR",                                  "titulo":"TMR"},
            {"campo":"vrSubtotalExtranjero",            "tipo":"moneda",    "ayuda":"Subtotal extranjero",                  "titulo":"SUBTOTAL EXTRANJERO"},
            {"campo":"vrTotalExtranjero",               "tipo":"moneda",    "ayuda":"Total extranjero",                     "titulo":"TOTAL EXTRANJERO"},
            {"campo":"vrSubtotalLocal",                 "tipo":"moneda",    "ayuda":"Subtotal local",                       "titulo":"SUBTOTAL LOCAL"},
            {"campo":"vrTotalLocal",                    "tipo":"moneda",    "ayuda":"Total local",                          "titulo":"TOTAL LOCAL"},
            {"campo":"estadoAutorizado",                "tipo":"bool",      "ayuda":"Autorizdo",                            "titulo":"AUT"},
            {"campo":"estadoAprobado",                  "tipo":"bool",      "ayuda":"Aprobado",                             "titulo":"APR"},
            {"campo":"estadoAnulado",                   "tipo":"bool",      "ayuda":"Anulado",                              "titulo":"ANU"}
                                     
        ]';
        return $campos;
    }

    public function getEstructuraPropiedadesFiltro()
    {
        $campos = '[
            {"child":"numero",                  "tipo":"TextType",    "propiedades":{"label":"Numero"}},
            {"child":"codigoImportacionPk",     "tipo":"TextType",    "propiedades":{"label":"Codigo"}},
            {"child":"codigoTerceroFk",         "tipo":"TextType",    "propiedades":{"label":"Codigo cliente"}},
            {"child":"codigoImportacionTipoFk", "tipo":"EntityType",  "propiedades":{"class":"InvImportacionTipo","choice_label":"nombre","label":"Tipo"}},
            {"child":"estadoAutorizado",        "tipo":"ChoiceType",  "propiedades":{"label":"Autorizado",    "choices":{"SI":true,"NO":false}}},
            {"child":"estadoAprobado",          "tipo":"ChoiceType",  "propiedades":{"label":"Aprobado",    "choices":{"SI":true,"NO":false}}},
            {"child":"estadoAnulado",           "tipo":"ChoiceType",  "propiedades":{"label":"Anulado",     "choices":{"SI":true,"NO":false}}}
        ]';
        return $campos;
    }

}
