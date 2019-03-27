<?php

namespace App\Form\Type\Inventario;

use App\Entity\Inventario\InvCotizacion;
use App\Entity\Inventario\InvCotizacionTipo;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CotizacionType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('cotizacionTipoRel',EntityType::class,[
                'required' => true,
                'class' => InvCotizacionTipo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('st')
                        ->orderBy('st.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Solicitud tipo:'
            ])
            ->add('asesorRel',EntityType::class,[
                'required' => true,
                'class' => 'App\Entity\General\GenAsesor',
                'query_builder' => function (EntityRepository $er) use ($options) {
                    return $er->createQueryBuilder('a')
                        ->orderBy('a.nombre', 'ASC');
                },
                'choice_label' => 'nombre'
            ])
            ->add('formaPagoRel',EntityType::class,[
                'required' => true,
                'class' => 'App\Entity\General\GenFormaPago',
                'query_builder' => function (EntityRepository $er) use ($options) {
                    return $er->createQueryBuilder('g')
                        ->orderBy('g.nombre', 'ASC');
                },
                'choice_label' => 'nombre'
            ])
            ->add('codigoTerceroFk',TextType::class,['required' => false ])
            ->add('codigoContactoFk',TextType::class,['required' => false ])
            ->add('costoEnvio',IntegerType::class, ['required' => false,'label' => 'Costo de envío:'])
            ->add('tiempoEntrega',TextType::class, ['required' => false,'label' => 'Dias entrega:'])
            ->add('vencimiento', DateType::class,array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('comentarios',TextareaType::class, ['required' => false,'label' => 'Comentarios:'])
            ->add('soporte',TextType::class, ['required' => false,'label' => 'Soporte:'])
            ->add('guardar', SubmitType::class, ['label'=>'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => InvCotizacion::class
        ));
    }

    public function getEstructuraPropiedadesLista()
    {
        $campos = '[
            {"campo":"codigoCotizacionPk",              "tipo":"pk",        "ayuda":"Codigo del registro",                  "titulo":"ID"},
            {"campo":"cotizacionTipoRel.nombre",        "tipo":"texto",     "ayuda":"Tipo de cotizacion",                   "titulo":"COTIZACION TIPO",         "relacion":""},
            {"campo":"terceroRel.nombreCorto",            "tipo":"texto",     "ayuda":"Tercero",                              "titulo":"TERCERO",         "relacion":""},
            {"campo":"numero",                          "tipo":"texto",     "ayuda":"Numero del registro",                  "titulo":"NUMERO"},
            {"campo":"fecha",                           "tipo":"fecha",     "ayuda":"Fecha del registro",                   "titulo":"FECHA"},
            {"campo":"vrSubtotal",                      "tipo":"moneda",    "ayuda":"Subtotal",                             "titulo":"SUBTOTAL"},
            {"campo":"vrIva",                           "tipo":"moneda",    "ayuda":"vrIva",                                "titulo":"IVA"},
            {"campo":"vrNeto",                          "tipo":"moneda",    "ayuda":"vrNeto",                               "titulo":"NETO"},
            {"campo":"vrTotal",                         "tipo":"moneda",    "ayuda":"Total",                                "titulo":"TOTAL"},
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
            {"child":"codigoCotizacionPk",     "tipo":"TextType",    "propiedades":{"label":"Codigo"}},
            {"child":"codigoTerceroFk",         "tipo":"TextType",    "propiedades":{"label":"Codigo cliente"}},
            {"child":"codigoCotizacionTipoFk", "tipo":"EntityType",  "propiedades":{"class":"InvCotizacionTipo","choice_label":"nombre","label":"Tipo"}},
            {"child":"estadoAutorizado",        "tipo":"ChoiceType",  "propiedades":{"label":"Autorizado",    "choices":{"SI":true,"NO":false}}},
            {"child":"estadoAprobado",          "tipo":"ChoiceType",  "propiedades":{"label":"Aprobado",    "choices":{"SI":true,"NO":false}}},
            {"child":"estadoAnulado",           "tipo":"ChoiceType",  "propiedades":{"label":"Anulado",     "choices":{"SI":true,"NO":false}}}
        ]';
        return $campos;
    }

}
