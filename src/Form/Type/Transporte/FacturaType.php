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

class FacturaType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('facturaTipoRel',EntityType::class,[
                'required' => false,
                'class' => 'App\Entity\Transporte\TteFacturaTipo',
                'query_builder' => function (EntityRepository $er) use ($options) {
                    return $er->createQueryBuilder('ft')
                        ->orderBy('ft.nombre', 'ASC')
                        ->where('ft.guiaFactura = 0')
                        ->andWhere("ft.codigoFacturaClaseFk = '". $options['data']->getCodigoFacturaClaseFk() ."'");
                },
                'choice_label' => 'nombre',
                'label' => 'Fecha tipo:',
                'required' => true
            ])
            ->add('soporte', TextType::class,['required' => false])
            ->add('plazoPago', NumberType::class)
            ->add('comentario',TextareaType::class ,['required' => false,'label' => 'Comentarios:'])
            ->add('guardar', SubmitType::class,array('label'=>'Guardar'))
            ->add('guardarnuevo', SubmitType::class,array('label'=>'Guardar y nuevo'));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\Transporte\TteFactura'
        ));
    }

    public function getEstructuraPropiedadesLista()
    {
        $campos = '[
            {"campo":"codigoFacturaPk",                         "tipo":"pk",        "ayuda":"Codigo de factura",                        "titulo":"ID"},
            {"campo":"facturaTipoRel.nombre",                   "tipo":"texto",     "ayuda":"Tipo factura",                             "titulo":"TIPO",                "relacion":""},
            {"campo":"numero",                                  "tipo":"entero",    "ayuda":"Numero",                                   "titulo":"NUMERO"},
            {"campo":"fecha",                                   "tipo":"fecha",     "ayuda":"Fecha",                                    "titulo":"FECHA"},
            {"campo":"clienteRel.nombreCorto",                  "tipo":"texto",     "ayuda":"Nombre del cliente",                       "titulo":"CLIENTE",             "relacion":""},
            {"campo":"guias",                                   "tipo":"entero",    "ayuda":"Cantidad",                                 "titulo":"CANT"},
            {"campo":"vrFlete",                                 "tipo":"moneda",    "ayuda":"Valor flete",                              "titulo":"FLETE"},
            {"campo":"vrManejo",                                "tipo":"moneda",    "ayuda":"Valor manejo",                             "titulo":"MANEJO"},
            {"campo":"vrSubtotal",                              "tipo":"moneda",    "ayuda":"Subtotal",                                 "titulo":"SUBTOTAL"},
            {"campo":"vrTotal",                                 "tipo":"moneda",    "ayuda":"Total",                                    "titulo":"TOTAL"},
            {"campo":"estadoAutorizado",                        "tipo":"bool",      "ayuda":"Autorizado",                               "titulo":"AUT"},
            {"campo":"estadoAprobado",                          "tipo":"bool",      "ayuda":"Aprobado",                                 "titulo":"APR"},
            {"campo":"estadoAnulado",                           "tipo":"bool",      "ayuda":"Anulado",                                  "titulo":"ANU"},
            {"campo":"codigoFacturaClaseFk",                    "tipo":"texto",     "ayuda":"Clase",                                    "titulo":"Clase"}
        ]';
        return $campos;

    }


    public function getEstructuraPropiedadesFiltro()
    {

        $campos = '[
            {"child":"codigoClienteFk",                 "tipo":"TextType",   "propiedades":{"label":"Cliente"}},
            {"child":"numero",                          "tipo":"TextType",   "propiedades":{"label":"Numero"}},
            {"child":"codigoFacturaPk",                 "tipo":"TextType",   "propiedades":{"label":"Codigo"}},
            {"child":"codigoFacturaTipoFk",             "tipo":"EntityType", "propiedades":{"class":"TteFacturaTipo",   "choice_label":"nombre",    "label":"TODOS"}},
            {"child":"fechaDesde",                      "tipo":"DateType",   "propiedades":{"label":"Fecha Desde"}},
            {"child":"fechaHasta",                      "tipo":"DateType",   "propiedades":{"label":"Fecha Hasta"}},
            {"child":"estadoAutorizado",                "tipo":"ChoiceType",   "propiedades":{"label":"Autorizado",     "choices":{"SI":true,"NO":false}}},
            {"child":"estadoAprobado",                  "tipo":"ChoiceType",   "propiedades":{"label":"Aprobado",       "choices":{"SI":true,"NO":false}}},
            {"child":"estadoAnulado",                   "tipo":"ChoiceType",   "propiedades":{"label":"Anulado",        "choices":{"SI":true,"NO":false}}}
        ]';

        return $campos;
    }


    public function getOrdenamiento(){
        $campos ='[
            {"campo":"estadoAprobado","tipo":"ASC"},
            {"campo":"fecha","tipo":"DESC"},
            {"campo":"codigoFacturaPk","tipo":"DESC"}
        ]';
        return $campos;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'App_factura';
    }

}
