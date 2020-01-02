<?php

namespace App\Form\Type\Transporte;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class   RecogidaType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('conductorRel',EntityType::class,[
                'required' => false,
                'class' => 'App\Entity\Transporte\TteConductor',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.nombreCorto', 'ASC');
                },
                'choice_label' => 'nombreCorto',
                'label' => 'Conductor:'
            ])
            ->add('vehiculoRel',EntityType::class,[
                'required' => false,
                'class' => 'App\Entity\Transporte\TteVehiculo',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('v')
                        ->orderBy('v.placa', 'ASC');
                },
                'choice_label' => 'placa',
                'label' => 'Vehiculo:'
            ])
            ->add('ciudadRel',EntityType::class,[
                'required' => true,
                'class' => 'App\Entity\Transporte\TteCiudad',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Ciudad:'
            ])
            ->add('rutaRecogidaRel',EntityType::class,[
                'required' => false,
                'class' => 'App\Entity\Transporte\TteRutaRecogida',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('rr')
                        ->orderBy('rr.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Ruta:'
            ])
            ->add("direccion",TextType::class,['required'=>true])
            ->add("telefono",TextType::class,['required'=>true])
            ->add('fecha', DateTimeType::class)
            ->add('unidades', NumberType::class)
            ->add('pesoReal', NumberType::class)
            ->add('pesoVolumen', NumberType::class)
            ->add('comentario', TextareaType::class,['required'=>false])
            ->add('guardar', SubmitType::class,array('label'=>'Guardar'))
            ->add('guardarnuevo', SubmitType::class, ['label'=>'Guardar y nuevo','attr' => ['class' => 'btn btn-sm btn-primary']]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\Transporte\TteRecogida'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'App_recogida';
    }

    public function getEstructuraPropiedadesLista()
    {
        $campos = '[
            {"campo":"codigoRecogidaPk",                        "tipo":"pk",        "ayuda":"Codigo de recogida",                       "titulo":"ID"},
            {"campo":"codigoOperacionFk",                       "tipo":"texto",     "ayuda":"Operacion",                                "titulo":"OP"},
            {"campo":"fechaRegistro",                           "tipo":"fecha",     "ayuda":"Fecha registro",                           "titulo":"REG"},
            {"campo":"fecha",                                   "tipo":"fecha",     "ayuda":"Fecha",                                    "titulo":"FECHA"},
            {"campo":"fecha",                                   "tipo":"hora",      "ayuda":"Hora",                                     "titulo":"HORA"},
            {"campo":"rutaRecogidaRel.nombre",                  "tipo":"texto",     "ayuda":"Ruta",                       "titulo":"RUTA",             "relacion":""},
            {"campo":"clienteRel.nombreCorto",                  "tipo":"texto",     "ayuda":"Nombre del cliente",                       "titulo":"CLIENTE",             "relacion":""},
            {"campo":"anunciante",                              "tipo":"texto",     "ayuda":"Anunciante",                               "titulo":"ANUNCIANTE"},
            {"campo":"direccion",                               "tipo":"texto",     "ayuda":"Direccion",                                "titulo":"DIRECCION"},
            {"campo":"ciudadRel.nombre",                        "tipo":"texto",     "ayuda":"Ciudad",                                   "titulo":"CIUDAD",              "relacion":""},
            {"campo":"telefono",                               "tipo":"texto",     "ayuda":"Telefono",                                 "titulo":"TELEFONO"},
            {"campo":"unidades",                                "tipo":"entero",    "ayuda":"Unidades",                                 "titulo":"UND"},
            {"campo":"pesoReal",                                "tipo":"entero",    "ayuda":"Peso real",                                "titulo":"PES"},
            {"campo":"pesoVolumen",                             "tipo":"entero",    "ayuda":"Peso volumen",                             "titulo":"VOL"},
            {"campo":"estadoAutorizado",                        "tipo":"bool",      "ayuda":"Autorizado",                               "titulo":"AUT"},
            {"campo":"estadoAprobado",                          "tipo":"bool",      "ayuda":"Aprobado",                                 "titulo":"APR"},
            {"campo":"estadoAnulado",                           "tipo":"bool",      "ayuda":"Anulado",                                  "titulo":"ANU"},
            {"campo":"estadoProgramado",                        "tipo":"bool",      "ayuda":"Programado",                               "titulo":"PRO"},
            {"campo":"estadoRecogido",                          "tipo":"bool",      "ayuda":"Recogido",                                 "titulo":"REC"},
            {"campo":"estadoDescargado",                        "tipo":"bool",      "ayuda":"",                               "titulo":""},
            {"campo":"comentario",                               "tipo":"texto",     "ayuda":"Comentario",                                 "titulo":"COMENTARIO"}
        ]';
        return $campos;

    }

    public function getEstructuraPropiedadesFiltro()
    {

        $campos = '[
            {"child":"codigoClienteFk", "tipo":"TextType",  "propiedades":{"label":"Cliente"}},
            {"child":"codigoRecogidaPk","tipo":"TextType",  "propiedades":{"label":"Codigo"}},
            {"child":"fechaDesde",                      "tipo":"DateType",   "propiedades":{"label":"Fecha Desde"}},
            {"child":"fechaHasta",                      "tipo":"DateType",   "propiedades":{"label":"Fecha Hasta"}},
            {"child":"estadoProgramado","tipo":"ChoiceType","propiedades":{"label":"Programado", "choices":{"SI":true,"NO":false}}},
            {"child":"estadoAutorizado","tipo":"ChoiceType","propiedades":{"label":"Autorizado", "choices":{"SI":true,"NO":false}}},
            {"child":"estadoAprobado",  "tipo":"ChoiceType","propiedades":{"label":"Aprobado",   "choices":{"SI":true,"NO":false}}},
            {"child":"estadoAnulado",   "tipo":"ChoiceType","propiedades":{"label":"Anulado",    "choices":{"SI":true,"NO":false}}}
        ]';

        return $campos;
    }


    public function getOrdenamiento(){
        $campos ='[	
            {"campo":"fecha","tipo":"DESC"}	
        ]';
        return $campos;
    }
}
