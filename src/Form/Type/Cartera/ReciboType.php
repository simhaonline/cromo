<?php

namespace App\Form\Type\Cartera;

use App\Entity\Cartera\CarRecibo;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ReciboType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('cuentaRel',EntityType::class,[
                'required' => false,
                'class' => 'App\Entity\General\GenCuenta',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Cuenta:',
                'required' => true
            ])
            ->add('reciboTipoRel',EntityType::class,[
                'required' => false,
                'class' => 'App\Entity\Cartera\CarReciboTipo',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('rt')
                        ->orderBy('rt.orden', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Tipo recibo:',
                'required' => true
            ])
            ->add('asesorRel',EntityType::class,[
                'required' => false,
                'class' => 'App\Entity\General\GenAsesor',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('a')
                        ->orderBy('a.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Asesor:'
            ])
            ->add('soporte', TextType::class, array('required' => false))
            ->add('fechaPago', DateType::class, array('format' => 'yyyyMMdd'))
            ->add('comentarios',TextareaType::class,['required' => false,'label' => 'Comentarios:'])
            ->add('numeroReferencia', NumberType::class, array('required' => false))
            ->add('numeroReferenciaPrefijo', TextType::class, array('required' => false))
            ->add('guardar', SubmitType::class, ['label'=>'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']])
            ->add('guardarnuevo', SubmitType::class, ['label'=>'Guardar y nuevo','attr' => ['class' => 'btn btn-sm btn-primary']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CarRecibo::class,
        ]);
    }

    public function getEstructuraPropiedadesLista()
    {
        $campos = '[
            {"campo":"codigoReciboPk",                 "tipo":"pk",    "ayuda":"Codigo del registro",                  "titulo":"ID"},
            {"campo":"numero",                         "tipo":"texto", "ayuda":"Consecutivo de aprobación",            "titulo":"NUMERO"},
            {"campo":"reciboTipoRel.nombre",           "tipo":"texto", "ayuda":"Tipo recibo",                          "titulo":"TIPO",          "relacion":""},
            {"campo":"fecha",                          "tipo":"fecha", "ayuda":"Fecha",                                "titulo":"FECHA"},
            {"campo":"fechaPago",                      "tipo":"fecha", "ayuda":"Fecha de pago",                        "titulo":"FECHA_PAGO"},
            {"campo":"clienteRel.numeroIdentificacion","tipo":"texto", "ayuda":"Numero de identificacion del tercero", "titulo":"IDENTIFICACION","relacion":""},
            {"campo":"clienteRel.nombreCorto",         "tipo":"texto", "ayuda":"Nombre del tercero",                   "titulo":"NOMBRE",        "relacion":""},
            {"campo":"cuentaRel.nombre",               "tipo":"texto", "ayuda":"Nombre de la cuenta",                  "titulo":"CUENTA",        "relacion":""},
            {"campo":"vrPago",                         "tipo":"moneda","ayuda":"Pago que realizo el cliente",          "titulo":"PAGO"},
            {"campo":"vrPagoTotal",                    "tipo":"moneda","ayuda":"Total",                                "titulo":"TOTAL"},
            {"campo":"usuario",                        "tipo":"texto", "ayuda":"Usuario",                              "titulo":"USU"},
            {"campo":"estadoAutorizado",               "tipo":"bool",  "ayuda":"Autorizado",                           "titulo":"AUT"},
            {"campo":"estadoAprobado",                 "tipo":"bool",  "ayuda":"Aprobado",                             "titulo":"APR"},
            {"campo":"estadoAnulado",                  "tipo":"bool",  "ayuda":"Anulado",                              "titulo":"ANU"}
        ]';
        return $campos;
    }

    public function getEstructuraPropiedadesFiltro()
    {

        $campos = '[
            {"child":"codigoClienteFk",    "tipo":"TextType",  "propiedades":{"label":"Cliente"}},
            {"child":"numero",             "tipo":"TextType",  "propiedades":{"label":"Numero"}},
            {"child":"codigoReciboPk",     "tipo":"TextType",  "propiedades":{"label":"Codigo"}},
            {"child":"codigoReciboTipoFk", "tipo":"EntityType","propiedades":{"class":"CarReciboTipo","choice_label":"nombre", "label":"TODOS"}},
            {"child":"codigoAsesorFk",     "tipo":"EntityType","propiedades":{"class":"GenAsesor","choice_label":"nombre", "label":"TODOS"}},
            {"child":"fechaPagoDesde",         "tipo":"DateType",  "propiedades":{"label":"Fecha Desde"}},
            {"child":"fechaPagoHasta",         "tipo":"DateType",  "propiedades":{"label":"Fecha Hasta"}},
            {"child":"estadoAutorizado",   "tipo":"ChoiceType","propiedades":{"label":"Autorizado",     "choices":{"SI":true,"NO":false}}},
            {"child":"estadoAprobado",     "tipo":"ChoiceType","propiedades":{"label":"Aprobado",       "choices":{"SI":true,"NO":false}}},
            {"child":"estadoAnulado",      "tipo":"ChoiceType","propiedades":{"label":"Anulado",        "choices":{"SI":true,"NO":false}}}
        ]';

        return $campos;
    }

    public function getOrdenamiento(){
        $campos ='[
            {"campo":"estadoAprobado","tipo":"ASC"},
            {"campo":"fecha","tipo":"DESC"},
            {"campo":"codigoReciboPk","tipo":"DESC"}
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
