<?php

namespace App\Form\Type\Transporte;

use App\Entity\Transporte\TteCiudad;
use App\Entity\Transporte\TteEmpaque;
use App\Entity\Transporte\TteGuiaTipo;
use App\Entity\Transporte\TteRuta;
use App\Entity\Transporte\TteServicio;
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

class GuiaType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('servicioRel', EntityType::class, array(
                'class' => TteServicio::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('s')
                        ->orderBy('s.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
            ))
            ->add('empaqueRel', EntityType::class, array(
                'class' => TteEmpaque::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('e')
                        ->orderBy('e.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
            ))
            /*->add('ciudadDestinoRel', EntityType::class, array(
                'class' => TteCiudad::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
            ))*/
            ->add('documentoCliente', TextType::class)
            ->add('remitente', TextType::class)
            ->add('nombreDestinatario', TextType::class)
            ->add('direccionDestinatario', TextType::class)
            ->add('telefonoDestinatario', TextType::class)
            ->add('unidades', NumberType::class)
            ->add('pesoReal', NumberType::class)
            ->add('pesoVolumen', NumberType::class)
            ->add('vrDeclara', NumberType::class)
            ->add('vrFlete', NumberType::class)
            ->add('vrManejo', NumberType::class)
            ->add('comentario',TextareaType::class, array('required' => false))
            ->add('guardar',SubmitType::class,['label' => 'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\Transporte\TteGuia'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'App_despacho';
    }

    public function getEstructuraPropiedadesLista()
    {
        $campos = '[	
            {"campo":"codigoGuiaPk",                            "tipo":"pk",        "ayuda":"Codigo de guia",                           "titulo":"GUIA"},	
            {"campo":"codigoOperacionIngresoFk",                "tipo":"texto",     "ayuda":"Operacion ingreso",                         "titulo":"OI"},	
            {"campo":"codigoOperacionCargoFk",                  "tipo":"texto",     "ayuda":"Operacion cargo",                   "titulo":"OC"},	
            {"campo":"codigoServicioFk",                        "tipo":"texto",     "ayuda":"Codigo servicio",                          "titulo":"SER"},	
            {"campo":"codigoGuiaTipoFk",                        "tipo":"texto",     "ayuda":"Codigo guia tipo",                         "titulo":"TIPO"},	
            {"campo":"numero",                                  "tipo":"entero",    "ayuda":"Numero",                                   "titulo":"NUMERO"},	
            {"campo":"documentoCliente",                        "tipo":"entero",    "ayuda":"Documento cliente",                        "titulo":"DOC"},	
            {"campo":"fechaIngreso",                            "tipo":"fecha",     "ayuda":"Fecha ingreso",                            "titulo":"FECHA"},	
            {"campo":"clienteRel.nombreCorto",                  "tipo":"texto",     "ayuda":"Nombre cliente",                           "titulo":"CLIENTE",         "relacion":""},	
            {"campo":"ciudadOrigenRel.nombre",                  "tipo":"texto",     "ayuda":"Ciudad Origen",                            "titulo":"ORIGEN",          "relacion":""},	
            {"campo":"ciudadDestinoRel.nombre",                 "tipo":"texto",     "ayuda":"Ciudad destino",                           "titulo":"DESTINO",         "relacion":""},	
            {"campo":"unidades",                                "tipo":"moneda",    "ayuda":"Unidades",                                 "titulo":"UND"},                                	
            {"campo":"pesoReal",                                "tipo":"moneda",    "ayuda":"Peso real",                                "titulo":"PES"},                                	
            {"campo":"pesoVolumen",                             "tipo":"moneda",    "ayuda":"Peso volumen",                             "titulo":"VOL"},	
            {"campo":"vrFlete",                                 "tipo":"moneda",    "ayuda":"Valor flete",                              "titulo":"FLETE"},	
            {"campo":"vrManejo",                                "tipo":"moneda",    "ayuda":"Valor manejo",                             "titulo":"MANEJO"},	
            {"campo":"vrRecaudo",                               "tipo":"moneda",    "ayuda":"Valor recaudo",                            "titulo":"REC"},	
            {"campo":"codigoDespachoFk",                        "tipo":"entero",    "ayuda":"Codigo del despacho",                       "titulo":"DES"},	
            {"campo":"cortesia",                                "tipo":"bool",      "ayuda":"Tipo de pago cortesia",                     "titulo":"COR"},	
            {"campo":"estadoAutorizado",                        "tipo":"bool",      "ayuda":"Autorizado",                               "titulo":"AUT"},	
            {"campo":"estadoAprobado",                          "tipo":"bool",      "ayuda":"Aprobado",                                 "titulo":"APR"},	
            {"campo":"estadoImpreso",                           "tipo":"bool",      "ayuda":"Impresa",                                  "titulo":"I"},	
            {"campo":"estadoEmbarcado",                         "tipo":"bool",      "ayuda":"Embarcada sin despachar",                  "titulo":"B"},	
            {"campo":"estadoDespachado",                        "tipo":"bool",      "ayuda":"Despachada",                               "titulo":"D"},	
            {"campo":"estadoEntregado",                         "tipo":"bool",      "ayuda":"Entregada",                                "titulo":"E"},	
            {"campo":"estadoSoporte",                           "tipo":"bool",      "ayuda":"Soporte de entrega",                       "titulo":"S"},	
            {"campo":"estadoCumplido",                          "tipo":"bool",      "ayuda":"Cumplido entregado al cliente",            "titulo":"C"},	
            {"campo":"estadoFacturado",                         "tipo":"bool",      "ayuda":"Facturado",                                "titulo":"F"},	
            {"campo":"estadoNovedad",                           "tipo":"bool",      "ayuda":"Con novedad",                              "titulo":"N"},	
            {"campo":"estadoAnulado",                           "tipo":"bool",      "ayuda":"Anulado",                                  "titulo":"A"}	
        ]';
        return $campos;
    }
    public function getEstructuraPropiedadesExportar()
    {
        $campos = '[	
            {"campo":"codigoGuiaPk",                            "tipo":"pk",        "ayuda":"Codigo de guia",                           "titulo":"GUIA"},	
            {"campo":"codigoOperacionIngresoFk",                "tipo":"texto",     "ayuda":"Operacion ingreso",                         "titulo":"OI"},	
            {"campo":"codigoOperacionCargoFk",                  "tipo":"texto",     "ayuda":"Operacion cargo",                   "titulo":"OC"},	
            {"campo":"codigoServicioFk",                        "tipo":"texto",     "ayuda":"Codigo control",                          "titulo":"SER"},	
            {"campo":"codigoGuiaTipoFk",                        "tipo":"texto",     "ayuda":"Codigo guia tipo",                         "titulo":"TIPO"},	
            {"campo":"numero",                                  "tipo":"entero",    "ayuda":"Numero",                                   "titulo":"NUMERO"},	
            {"campo":"documentoCliente",                        "tipo":"entero",    "ayuda":"Documento cliente",                        "titulo":"DOC"},	
            {"campo":"fechaIngreso",                            "tipo":"fecha",     "ayuda":"Fecha ingreso",                            "titulo":"FECHA"},	
            {"campo":"fechaEntrega",                            "tipo":"fecha",     "ayuda":"Fecha entrega",                            "titulo":"ENTREGA"},	
            {"campo":"clienteRel.nombreCorto",                  "tipo":"texto",     "ayuda":"Nombre cliente",                           "titulo":"CLIENTE",         "relacion":""},	
            {"campo":"nombreDestinatario",                  "tipo":"texto",     "ayuda":"Destinatario",                           "titulo":"DESTINATARIO"},	
            {"campo":"ciudadDestinoRel.nombre",                 "tipo":"texto",     "ayuda":"Ciudad destino",                           "titulo":"DESTINO",         "relacion":""},	
            {"campo":"unidades",                                "tipo":"moneda",    "ayuda":"Unidades",                                 "titulo":"UND"},                                	
            {"campo":"pesoReal",                                "tipo":"moneda",    "ayuda":"Peso real",                                "titulo":"PES"},                                	
            {"campo":"pesoVolumen",                             "tipo":"moneda",    "ayuda":"Peso volumen",                             "titulo":"VOL"},	
            {"campo":"vrFlete",                                 "tipo":"moneda",    "ayuda":"Valor flete",                              "titulo":"FLETE"},	
            {"campo":"vrManejo",                                "tipo":"moneda",    "ayuda":"Valor manejo",                             "titulo":"MANEJO"},	
            {"campo":"vrRecaudo",                               "tipo":"moneda",    "ayuda":"Valor recaudo",                            "titulo":"REC"},	
            {"campo":"usuario",                  "tipo":"texto",     "ayuda":"Usuario",                           "titulo":"USUARIO"},            	
            {"campo":"estadoEmbarcado",                         "tipo":"bool",      "ayuda":"Embarcada sin despachar",                  "titulo":"EMB"},	
            {"campo":"estadoDespachado",                        "tipo":"bool",      "ayuda":"Despachada",                               "titulo":"DES"},	
            {"campo":"estadoEntregado",                         "tipo":"bool",      "ayuda":"Entregada",                                "titulo":"ENT"},	
            {"campo":"estadoSoporte",                           "tipo":"bool",      "ayuda":"Soporte de entrega",                       "titulo":"SOP"},	
            {"campo":"estadoCumplido",                          "tipo":"bool",      "ayuda":"Cumplido entregado al cliente",            "titulo":"CUM"},	
            {"campo":"estadoFacturado",                         "tipo":"bool",      "ayuda":"Facturado",                                "titulo":"FAC"},	
            {"campo":"estadoNovedad",                           "tipo":"bool",      "ayuda":"Con novedad",                              "titulo":"NOV"},	
            {"campo":"estadoAnulado",                           "tipo":"bool",      "ayuda":"Anulado",                                  "titulo":"ANU"}	
        ]';
        return $campos;
    }
    public function getEstructuraPropiedadesFiltro()
    {
        //se anexa la posicion 'pk' cuando el campo 'fk' se llama distinto al 'pk', es decir que no solamente se cambia la palabra 'pk ' por 'fk'
        //se anexa la posicion operador cuando se va acompara un valor en la consulta diferente de '='
        $campos = '[	
            {"child":"codigoClienteFk",                 "tipo":"TextType",   "propiedades":{"label":"Cliente"}},	
            {"child":"codigoGuiaTipoFk",                "tipo":"EntityType", "propiedades":{"class":"TteGuiaTipo",  "choice_label":"nombre","label":"TODOS"}},	
            {"child":"codigoOperacionCargoFk",          "tipo":"EntityType", "propiedades":{"class":"TteOperacion", "choice_label":"nombre","label":"TODOS"},       "pk":"codigoOperacionPk"},	
            {"child":"codigoServicioFk",                "tipo":"EntityType", "propiedades":{"class":"TteServicio",  "choice_label":"nombre","label":"TODOS"}},	
            {"child":"codigoGuiaPk",                    "tipo":"TextType",   "propiedades":{"label":"Guía"}},	
            {"child":"codigoDespachoFk",                "tipo":"TextType",   "propiedades":{"label":"Despacho"}},	
            {"child":"numero",                          "tipo":"TextType",   "propiedades":{"label":"Numero"}},	
            {"child":"numeroFactura",                   "tipo":"TextType",   "propiedades":{"label":"Numero factura"}},	
            {"child":"documentoCliente",                "tipo":"TextType",   "propiedades":{"label":"Documento"},       "operador":"like"},	
            {"child":"fechaIngresoDesde",               "tipo":"DateType",   "propiedades":{"label":"Fecha Desde"}},	
            {"child":"fechaIngresoHasta",               "tipo":"DateType",   "propiedades":{"label":"Fecha Hasta"}},	
            {"child":"codigoFacturaFk",                 "tipo":"TextType",   "propiedades":{"label":"Factura"}},	
            {"child":"estadoFacturado",                 "tipo":"ChoiceType", "propiedades":{"label":"Facturado",    "choices":{"SI":true,"NO":false}}},	
            {"child":"estadoDespachado",                "tipo":"ChoiceType", "propiedades":{"label":"Despachado",   "choices":{"SI":true,"NO":false}}},	
            {"child":"estadoNovedad",                   "tipo":"ChoiceType", "propiedades":{"label":"Novedad",   "choices":{"SI":true,"NO":false}}},	
            {"child":"estadoNovedadSolucion",           "tipo":"ChoiceType", "propiedades":{"label":"Novedad solucionada",   "choices":{"SI":true,"NO":false}}},	
            {"child":"estadoAnulado",           "tipo":"ChoiceType", "propiedades":{"label":"Anulado",   "choices":{"SI":true,"NO":false}}},	
            {"child":"remitente",                       "tipo":"TextType",   "propiedades":{"label":"Remitente"}},	
            {"child":"nombreDestinatario",                       "tipo":"TextType",   "propiedades":{"label":"Destinatario"}}	
        ]';
        return $campos;
    }
    public function getOrdenamiento(){
        $campos ='[	
            {"campo":"fechaIngreso","tipo":"DESC"}	
        ]';
        return $campos;
    }

}
