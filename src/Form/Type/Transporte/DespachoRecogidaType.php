<?php

namespace App\Form\Type\Transporte;

use App\Entity\Seguridad\Usuario;
use App\Entity\Transporte\TteDespachoRecogidaTipo;
use App\Entity\Transporte\TteRutaRecogida;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

class DespachoRecogidaType extends AbstractType {
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('despachoRecogidaTipoRel', EntityType::class, array(
                'class' => TteDespachoRecogidaTipo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('drt')
                        ->orderBy('drt.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
            ))
            ->add('rutaRecogidaRel', EntityType::class, array(
                'class' => TteRutaRecogida::class,
                'query_builder' => function (EntityRepository $er) use ($options) {
                    return $er->createQueryBuilder('rr')
                        ->orderBy('rr.nombre', 'ASC')
                        ->where("rr.codigoOperacionFk = '". $options['data']->getOperacionRel()->getCodigoOperacionPk() ."'");
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
            'data_class' => 'App\Entity\Transporte\TteDespachoRecogida'
        ));
    }


    public function getEstructuraPropiedadesLista()
    {
        $campos = '[
            {"campo":"codigoDespachoRecogidaPk",                "tipo":"pk",        "ayuda":"Codigo despacho recogida",                 "titulo":"ID"},
            {"campo":"numero",                                  "tipo":"entero",    "ayuda":"Numero",                                   "titulo":"NUMERO"},
            {"campo":"fecha",                                   "tipo":"fecha",     "ayuda":"Fecha",                                    "titulo":"FECHA"},
            {"campo":"codigoOperacionFk",                       "tipo":"texto",     "ayuda":"Codigo operacion",                         "titulo":"OP"},
            {"campo":"codigoVehiculoFk",                        "tipo":"texto",     "ayuda":"Codigo vehiculo",                          "titulo":"VEH"},
            {"campo":"conductorRel.nombreCorto",                "tipo":"texto",     "ayuda":"Conductor",                                "titulo":"CONDUCTOR",                "relacion":""},
            {"campo":"rutaRecogidaRel.nombre",                "tipo":"texto",     "ayuda":"Ruta",                                "titulo":"RUTA",                "relacion":""},
            {"campo":"cantidad",                                "tipo":"entero",    "ayuda":"Cantidad",                                 "titulo":"CAN"},
            {"campo":"unidades",                                "tipo":"entero",    "ayuda":"Unidad",                                   "titulo":"UND"},
            {"campo":"pesoReal",                                "tipo":"entero",    "ayuda":"Peso real",                                "titulo":"PES"},
            {"campo":"pesoVolumen",                             "tipo":"entero",    "ayuda":"Peso volumen",                             "titulo":"VOL"},
            {"campo":"vrFletePago",                             "tipo":"moneda",    "ayuda":"Valor flete pago",                         "titulo":"PAGO"},
            {"campo":"estadoAutorizado",                        "tipo":"bool",      "ayuda":"Autorizado",                               "titulo":"AUT"},
            {"campo":"estadoAprobado",                          "tipo":"bool",      "ayuda":"Aprobado",                                 "titulo":"APR"},
            {"campo":"estadoAnulado",                           "tipo":"bool",      "ayuda":"Anulado",                                  "titulo":"ANU"}
        ]';
        return $campos;

    }

    public function getEstructuraPropiedadesFiltro()
    {

        $campos = '[
            {"child":"codigoVehiculoFk",                "tipo":"TextType",      "propiedades":{"label":"Vehiculo"}},
            {"child":"codigoDespachoRecogidaPk",        "tipo":"TextType",      "propiedades":{"label":"Codigo"}},
            {"child":"numero",                          "tipo":"TextType",      "propiedades":{"label":"Numero"}},
            {"child":"codigoOperacionFk",               "tipo":"EntityType",    "propiedades":{"class":"TteOperacion",   "choice_label":"nombre","label":"TODOS"}},
            {"child":"estadoAutorizado",                "tipo":"ChoiceType",    "propiedades":{"label":"Autorizado",     "choices":{"SI":true,"NO":false}}},
            {"child":"estadoAprobado",                  "tipo":"ChoiceType",    "propiedades":{"label":"Aprobado",       "choices":{"SI":true,"NO":false}}},
            {"child":"estadoAnulado",                   "tipo":"ChoiceType",    "propiedades":{"label":"Anulado",        "choices":{"SI":true,"NO":false}}},
            {"child":"fechaDesde",                      "tipo":"DateType",      "propiedades":{"label":"Fecha Desde"}},
            {"child":"fechaHasta",                      "tipo":"DateType",      "propiedades":{"label":"Fecha Hasta"}}
        ]';

        return $campos;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'App_despacho_recogida';
    }

}
