<?php

namespace App\Form\Type\Transporte;

use App\Entity\Transporte\TteDespachoTipo;
use App\Entity\Transporte\TteRuta;
use App\Entity\Transporte\TteCiudad;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

class DespachoType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('despachoTipoRel', EntityType::class, array(
                'class' => TteDespachoTipo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('dt')
                        ->orderBy('dt.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
            ))
            ->add('ciudadOrigenRel', EntityType::class, array(
                'class' => TteCiudad::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
            ))
            ->add('ciudadDestinoRel', EntityType::class, array(
                'class' => TteCiudad::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
            ))
            ->add('rutaRel', EntityType::class, array(
                'class' => TteRuta::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('rr')
                        ->orderBy('rr.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
            ))
            ->add('vrFletePago', NumberType::class)
            ->add('vrAnticipo', NumberType::class)
            ->add('vrDescuentoPapeleria', NumberType::class)
            ->add('vrDescuentoSeguridad', NumberType::class)
            ->add('vrDescuentoCargue', NumberType::class)
            ->add('vrDescuentoEstampilla', NumberType::class)
            ->add('comentario',TextareaType::class, array('required' => false))
            ->add('guardar', SubmitType::class,array('label'=>'Guardar'));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\Transporte\TteDespacho'
        ));
    }

    public function getEstructuraPropiedadesLista()
    {
        $campos = '[
            {"campo":"codigoDespachoPk",                        "tipo":"pk",        "ayuda":"Codigo de despacho",                       "titulo":"ID"},
            {"campo":"codigoOperacionFk",                       "tipo":"texto",     "ayuda":"Codigo de operacion",                      "titulo":"OP"},
            {"campo":"despachoTipoRel.nombre",                  "tipo":"texto",     "ayuda":"Despacho tipo",                            "titulo":"TIPO",                "relacion":""},
            {"campo":"numero",                                  "tipo":"entero",    "ayuda":"Numero",                                   "titulo":"NUMERO"},
            {"campo":"fechaSalida",                             "tipo":"fecha",     "ayuda":"Fecha salida",                             "titulo":"FECHA"},
            {"campo":"codigoVehiculoFk",                        "tipo":"texto",     "ayuda":"Vehiculo",                                 "titulo":"VEH"},
            {"campo":"conductorRel.nombreCorto",                "tipo":"texto",     "ayuda":"Conductor",                                "titulo":"CONDUCTOR",           "relacion":""},
            {"campo":"codigoRutaFk",                            "tipo":"texto",     "ayuda":"Codigo ruta",                              "titulo":"RUTA"},
            {"campo":"ciudadOrigenRel.nombre",                  "tipo":"texto",     "ayuda":"Ciudad origen",                            "titulo":"ORIGEN",              "relacion":""},
            {"campo":"ciudadDestinoRel.nombre",                 "tipo":"texto",     "ayuda":"Ciudad destino",                           "titulo":"DESTINO",             "relacion":""},
            {"campo":"vrFletePago",                             "tipo":"moneda",    "ayuda":"Valor flete pago",                         "titulo":"FLETE"},
            {"campo":"cantidad",                                "tipo":"moneda",    "ayuda":"Cantidad",                                 "titulo":"CANT"},
            {"campo":"unidades",                                "tipo":"moneda",    "ayuda":"Unidades",                                 "titulo":"UND"},
            {"campo":"pesoReal",                                "tipo":"moneda",    "ayuda":"Peso real",                                "titulo":"PES"},
            {"campo":"estadoAutorizado",                        "tipo":"bool",      "ayuda":"Autorizado",                               "titulo":"AUT"},
            {"campo":"estadoAprobado",                          "tipo":"bool",      "ayuda":"Aprobado",                                 "titulo":"APR"},
            {"campo":"estadoAnulado",                           "tipo":"bool",      "ayuda":"Anulado",                                  "titulo":"ANU"}
        ]';
        return $campos;

    }


    public function getEstructuraPropiedadesFiltro()
    {

        $campos = '[
            {"child":"codigoConductorFk",               "tipo":"TextType",   "propiedades":{"label":"Conductor"}},
            {"child":"codigoDespachoPk",                "tipo":"TextType",   "propiedades":{"label":"Codigo"}},
            {"child":"numero",                          "tipo":"TextType",   "propiedades":{"label":"Numero"}},
            {"child":"codigoVehiculoFk",                "tipo":"TextType",   "propiedades":{"label":"Numero"}},
            {"child":"codigoCiudadOrigenFk",            "tipo":"EntityType", "propiedades":{"class":"TteCiudad",        "choice_label":"nombre",    "label":"TODOS"}},
            {"child":"codigoCiudadDestinoFk",           "tipo":"EntityType", "propiedades":{"class":"TteCiudad",        "choice_label":"nombre",    "label":"TODOS"}},
            {"child":"codigoDespachoTipoFk",            "tipo":"EntityType", "propiedades":{"class":"TteDespachoTipo",  "choice_label":"nombre",    "label":"TODOS"}},
            {"child":"codigoOperacionFk",               "tipo":"EntityType", "propiedades":{"class":"TteOperacion",     "choice_label":"nombre",    "label":"TODOS"}},
            {"child":"fechaSalidaDesde",                      "tipo":"DateType",   "propiedades":{"label":"Fecha Desde"}},
            {"child":"fechaSalidaHasta",                      "tipo":"DateType",   "propiedades":{"label":"Fecha Hasta"}},
            {"child":"estadoAutorizado",                "tipo":"ChoiceType",   "propiedades":{"label":"Autorizado",     "choices":{"SI":true,"NO":false}}},
            {"child":"estadoAprobado",                  "tipo":"ChoiceType",   "propiedades":{"label":"Aprobado",       "choices":{"SI":true,"NO":false}}},
            {"child":"estadoAnulado",                   "tipo":"ChoiceType",   "propiedades":{"label":"Anulado",        "choices":{"SI":true,"NO":false}}}
        ]';

        return $campos;
    }


    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'App_despacho';
    }

}
