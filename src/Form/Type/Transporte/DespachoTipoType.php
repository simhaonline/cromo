<?php

namespace App\Form\Type\Transporte;

use App\Entity\Transporte\TteDespachoTipo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DespachoTipoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoDespachoTipoPk',TextType::class,['required' => true,'label' => 'Codigo despacho tipo pk:'])
            ->add('nombre',TextType::class,['required' => true,'label' => 'Nombre:'])
            ->add('consecutivo',NumberType::class,['required' => true,'label' => 'Consecutivo:'])
            ->add('exigeNumero', CheckboxType::class, array('required'  => false))
            ->add('generaMonitoreo', CheckboxType::class, array('required'  => false))
            ->add('viaje', CheckboxType::class, array('required'  => false))
            ->add('contabilizar', CheckboxType::class, array('required'  => false))
            ->add('codigoComprobanteFk',TextType::class,['required' => false,'label' => 'Codigo comprobante:'])
            ->add('codigoCuentaFleteFk',TextType::class,['required' => false,'label' => 'Codigo cuenta flete:'])
            ->add('codigoCuentaRetencionFuenteFk',TextType::class,['required' => false,'label' => 'Codigo cuenta retencion fuente:'])
            ->add('codigoCuentaIndustriaComercioFk',TextType::class,['required' => false,'label' => 'Codigo cuenta industria comercio:'])
            ->add('codigoCuentaSeguridadFk',TextType::class,['required' => false,'label' => 'Codigo cuenta seguridad:'])
            ->add('codigoCuentaCargueFk',TextType::class,['required' => false,'label' => 'Codigo cuenta cargue:'])
            ->add('codigoCuentaEstampillaFk',TextType::class,['required' => false,'label' => 'Codigo cuenta estampilla:'])
            ->add('codigoCuentaPapeleriaFk',TextType::class,['required' => false,'label' => 'Codigo cuenta papeleria:'])
            ->add('codigoCuentaAnticipoFk',TextType::class,['required' => false,'label' => 'Codigo cuenta anticipo:'])
            ->add('codigoCuentaPagarFk',TextType::class,['required' => false,'label' => 'Codigo cuenta pagar:'])
            ->add('guardar', SubmitType::class, ['label'=>'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TteDespachoTipo::class,
        ]);
    }

    public function getEstructuraPropiedadesLista(){
        return '[
            {"campo":"codigoDespachoTipoPk",            "tipo":"pk",        "ayuda":"Codigo del registro",     "titulo":"ID"},
            {"campo":"nombre",                          "tipo":"texto",     "ayuda":"Nombre del registro",     "titulo":"NOMBRE"},
            {"campo":"consecutivo",                     "tipo":"numero",     "ayuda":"Consecutivo",             "titulo":"CONSECUTIVO"},
            {"campo":"viaje",                           "tipo":"bool",     "ayuda":"Viaje",                     "titulo":"VIAJE"},
            {"campo":"exigeNumero",                     "tipo":"bool",     "ayuda":"Exige numero",              "titulo":"EXIGE NUMERO"},
            {"campo":"generaCuentaPagar",               "tipo":"bool",     "ayuda":"Genera cuenta pagar",       "titulo":"GENERA CUENTA COBRAR"},
            {"campo":"codigoComprobanteFk",             "tipo":"text",     "ayuda":"Codigo comprobante",        "titulo":"CODIGO COMPROBANTE"},
            {"campo":"codigoCuentaFleteFk",             "tipo":"text",     "ayuda":"Codigo cuenta flete",        "titulo":"CODIGO CUENTA FLETE"},
            {"campo":"codigoCuentaRetencionFuenteFk",   "tipo":"text",     "ayuda":"Codigo cuenta retencion fuente","titulo":"CODIGO CUENTA RETENCION FUENTE"},
            {"campo":"codigoCuentaIndustriaComercioFk", "tipo":"text",     "ayuda":"Codigo cuenta industria comercio","titulo":"CODIGO CUENTA INDUSTRIA COMERCIO"},
            {"campo":"codigoCuentaSeguridadFk",         "tipo":"text",     "ayuda":"Codigo cuenta seguridad",   "titulo":"CODIGO CUENTA SEGURIDAD"},
            {"campo":"codigoCuentaCargueFk",            "tipo":"text",     "ayuda":"Codigo cuenta cargue",      "titulo":"CODIGO CUENTA CARGUE"},
            {"campo":"codigoCuentaEstampillaFk",        "tipo":"text",     "ayuda":"Codigo cuenta estampilla",  "titulo":"CODIGO CUENTA ESTAMPILLA"},
            {"campo":"codigoCuentaPapeleriaFk",         "tipo":"text",     "ayuda":"Codigo cuenta papeleria",   "titulo":"CODIGO CUENTA PAPELERIA"},
            {"campo":"codigoCuentaAnticipoFk",          "tipo":"text",     "ayuda":"Codigo cuenta anticipo",    "titulo":"CODIGO CUENTA ANTICIPO"},
            {"campo":"codigoCuentaPagarFk",             "tipo":"text",     "ayuda":"Codigo cuenta pagar",    "titulo":"CODIGO CUENTA PAGAR"},
            {"campo":"codigoCuentaPagarTipoFk",         "tipo":"text",     "ayuda":"Codigo cuenta pagar tipo",    "titulo":"CODIGO CUENTA PAGAR TIPO"}
        ]';
    }

    public function getEstructuraPropiedadesExportar(){
        return '[
            {"campo":"codigoDespachoTipoPk",            "tipo":"pk",        "ayuda":"Codigo del registro",     "titulo":"ID"},
            {"campo":"nombre",                          "tipo":"texto",     "ayuda":"Nombre del registro",     "titulo":"NOMBRE"},
            {"campo":"consecutivo",                     "tipo":"numero",     "ayuda":"Consecutivo",             "titulo":"CONSECUTIVO"},
            {"campo":"viaje",                           "tipo":"bool",     "ayuda":"Viaje",                     "titulo":"VIAJE"},
            {"campo":"exigeNumero",                     "tipo":"bool",     "ayuda":"Exige numero",              "titulo":"EXIGE NUMERO"},
            {"campo":"generaCuentaPagar",               "tipo":"bool",     "ayuda":"Genera cuenta pagar",       "titulo":"GENERA CUENTA COBRAR"},
            {"campo":"codigoComprobanteFk",             "tipo":"text",     "ayuda":"Codigo comprobante",        "titulo":"CODIGO COMPROBANTE"},
            {"campo":"codigoCuentaFleteFk",             "tipo":"text",     "ayuda":"Codigo cuenta flete",        "titulo":"CODIGO CUENTA FLETE"},
            {"campo":"codigoCuentaRetencionFuenteFk",   "tipo":"text",     "ayuda":"Codigo cuenta retencion fuente","titulo":"CODIGO CUENTA RETENCION FUENTE"},
            {"campo":"codigoCuentaIndustriaComercioFk", "tipo":"text",     "ayuda":"Codigo cuenta industria comercio","titulo":"CODIGO CUENTA INDUSTRIA COMERCIO"},
            {"campo":"codigoCuentaSeguridadFk",         "tipo":"text",     "ayuda":"Codigo cuenta seguridad",   "titulo":"CODIGO CUENTA SEGURIDAD"},
            {"campo":"codigoCuentaCargueFk",            "tipo":"text",     "ayuda":"Codigo cuenta cargue",      "titulo":"CODIGO CUENTA CARGUE"},
            {"campo":"codigoCuentaEstampillaFk",        "tipo":"text",     "ayuda":"Codigo cuenta estampilla",  "titulo":"CODIGO CUENTA ESTAMPILLA"},
            {"campo":"codigoCuentaPapeleriaFk",         "tipo":"text",     "ayuda":"Codigo cuenta papeleria",   "titulo":"CODIGO CUENTA PAPELERIA"},
            {"campo":"codigoCuentaAnticipoFk",          "tipo":"text",     "ayuda":"Codigo cuenta anticipo",    "titulo":"CODIGO CUENTA ANTICIPO"},
            {"campo":"codigoCuentaPagarFk",             "tipo":"text",     "ayuda":"Codigo cuenta pagar",    "titulo":"CODIGO CUENTA PAGAR"},
            {"campo":"codigoCuentaPagarTipoFk",         "tipo":"text",     "ayuda":"Codigo cuenta pagar tipo",    "titulo":"CODIGO CUENTA PAGAR TIPO"}
        ]';
    }
}
