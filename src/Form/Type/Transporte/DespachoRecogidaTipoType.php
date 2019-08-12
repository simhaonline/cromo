<?php

namespace App\Form\Type\Transporte;

use App\Entity\Transporte\TteDespachoRecogidaTipo;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DespachoRecogidaTipoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoDespachoRecogidaTipoPk',TextType::class,['required' => true,'label' => 'Codigo despacho recogida tipo pk:'])
            ->add('nombre',TextType::class,['required' => true,'label' => 'Nombre:'])
            ->add('cuentaPagarTipoRel',EntityType::class,[
                'required' => false,
                'class' => 'App\Entity\Tesoreria\TesCuentaPagarTipo',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cpt')
                        ->orderBy('cpt.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Tipo cuenta por pagar:'
            ])
            ->add('consecutivo',NumberType::class,['required' => true,'label' => 'Consecutivo:'])
            ->add('generaMonitoreo', CheckboxType::class, array('required'  => false))
            ->add('generaCuentaPagar', CheckboxType::class, array('required'  => false))
            ->add('codigoComprobanteFk',TextType::class,['label' => 'Codigo comprobante fk:',  'required' => true])
            ->add('codigoCuentaFleteFk',TextType::class,['label' => 'Codigo cuenta flete fk:','required' => true])
            ->add('codigoCuentaRetencionFuenteFk',TextType::class,['label' => 'Codigo cuenta retencion fuente fk:', 'required' => true])
            ->add('codigoCuentaIndustriaComercioFk',TextType::class,['label' => 'Codigo cuenta industria y comercio fk:', 'required' => true])
            ->add('codigoCuentaSeguridadFk',TextType::class,['label' => 'Codigo cuenta seguridad fk:', 'required' => true])
            ->add('codigoCuentaEstampillaFk',TextType::class,['label' => 'Codigo cuenta estampilla fk:', 'required' => true])
            ->add('codigoCuentaPapeleriaFk',TextType::class,['label' => 'Codigo cuenta papeleria fk:', 'required' => true])
            ->add('codigoCuentaAnticipoFk',TextType::class,['label' => 'Codigo cuenta anticipo fk:', 'required' => true])
            ->add('codigoCuentaPagarFk',TextType::class,['label' => 'Codigo cuenta pagar fk:', 'required' => true])
            ->add('guardar', SubmitType::class, ['label'=>'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TteDespachoRecogidaTipo::class,
        ]);
    }

    public function getEstructuraPropiedadesLista(){
        return '[
            {"campo":"codigoDespachoRecogidaTipoPk",         "tipo":"pk",        "ayuda":"Codigo del registro",     "titulo":"ID"},
            {"campo":"nombre",                              "tipo":"texto",     "ayuda":"Nombre del registro",     "titulo":"NOMBRE"},
            {"campo":"consecutivo",                         "tipo":"numero",     "ayuda":"Consecutivo",             "titulo":"CONS"},
            {"campo":"generaMonitoreo",                     "tipo":"texto",     "ayuda":"Genera monitoreo",         "titulo":"MON"},
            {"campo":"generaCuentaPagar",               "tipo":"bool",     "ayuda":"Genera cuenta pagar",       "titulo":"GEN(CXP)"},
            {"campo":"codigoCuentaPagarTipoFk",         "tipo":"text",     "ayuda":"Codigo cuenta pagar tipo",    "titulo":"TIPO(CXP)"},
            {"campo":"codigoComprobanteFk",                 "tipo":"texto",     "ayuda":"Codigo comprobante",       "titulo":"COMP"},
            {"campo":"codigoCuentaFleteFk",                 "tipo":"texto",     "ayuda":"Codigo cuenta flete",      "titulo":"CTA FLETE"},
            {"campo":"codigoCuentaRetencionFuenteFk",       "tipo":"texto",     "ayuda":"Codigo cuenta retencion fuente","titulo":"CTA RTE FTE"},
            {"campo":"codigoCuentaIndustriaComercioFk",     "tipo":"texto",     "ayuda":"Codigo cuenta industria comercio","titulo":"CTA ICA"},
            {"campo":"codigoCuentaSeguridadFk",             "tipo":"texto",     "ayuda":"Codigo cuenta seguridad",   "titulo":"CTA SEG"},
            {"campo":"codigoCuentaCargueFk",                "tipo":"texto",     "ayuda":"Codigo cuenta cargue",     "titulo":"CTA CAR"},
            {"campo":"codigoCuentaEstampillaFk",            "tipo":"texto",     "ayuda":"Codigo cuenta estampilla",  "titulo":"CTA EST"},
            {"campo":"codigoCuentaPapeleriaFk",             "tipo":"texto",     "ayuda":"Codigo cuenta papeleria",  "titulo":"CTA PAP"},
            {"campo":"codigoCuentaAnticipoFk",             "tipo":"texto",     "ayuda":"Codigo cuenta anticipo",  "titulo":"CTA ANT"},
            {"campo":"codigoCuentaPagarFk",                 "tipo":"texto",     "ayuda":"Codigo cuenta pagar",  "titulo":"CTA PAG"}
        ]';
    }

    public function getEstructuraPropiedadesExportar(){
        return '[
            {"campo":"codigoDespachoRecogidaTipoPk",         "tipo":"pk",        "ayuda":"Codigo del registro",     "titulo":"ID"},
            {"campo":"nombre",                              "tipo":"texto",     "ayuda":"Nombre del registro",     "titulo":"NOMBRE"},
            {"campo":"consecutivo",                         "tipo":"numero",     "ayuda":"Consecutivo",             "titulo":"CONSECUTIVO"},
            {"campo":"generaMonitoreo",                     "tipo":"texto",     "ayuda":"Genera monitoreo",         "titulo":"GENERA MONITOREO"},
            {"campo":"codigoComprobanteFk",                 "tipo":"texto",     "ayuda":"Codigo comprobante",       "titulo":"CODIGO COMPROBANTE"},
            {"campo":"codigoCuentaFleteFk",                 "tipo":"texto",     "ayuda":"Codigo cuenta flete",      "titulo":"CODIGO CUENTA FLETE"},
            {"campo":"codigoCuentaRetencionFuenteFk",       "tipo":"texto",     "ayuda":"Codigo cuenta retencion fuente","titulo":"CODIGO CUENTA RETENCION FUENTE"},
            {"campo":"codigoCuentaIndustriaComercioFk",     "tipo":"texto",     "ayuda":"Codigo cuenta industria comercio","titulo":"CODIGO CUENTA INDUSTRIA COMERCIO"},
            {"campo":"codigoCuentaSeguridadFk",             "tipo":"texto",     "ayuda":"Codigo cuenta seguridad",   "titulo":"CODIGO CUENTA SEGURIDAD"},
            {"campo":"codigoCuentaCargueFk",                "tipo":"texto",     "ayuda":"Codigo cuenta cargue",     "titulo":"CODIGO CUENTA CARGUE"},
            {"campo":"codigoCuentaEstampillaFk",            "tipo":"texto",     "ayuda":"Codigo cuenta estampilla",  "titulo":"CODIGO CUENTA ESTAMPILLA"},
            {"campo":"codigoCuentaPapeleriaFk",             "tipo":"texto",     "ayuda":"Codigo cuenta papeleria",  "titulo":"CODIGO CUENTA PAPELERIA"},
            {"campo":"codigoCuentaAnticipoFk",             "tipo":"texto",     "ayuda":"Codigo cuenta anticipo",  "titulo":"CODIGO CUENTA ANTICIPO"},
            {"campo":"codigoCuentaPagarFk",                 "tipo":"texto",     "ayuda":"Codigo cuenta pagar",  "titulo":"CODIGO CUENTA PAGAR"}
        ]';
    }
}
