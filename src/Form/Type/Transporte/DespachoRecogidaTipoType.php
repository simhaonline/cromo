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
            ->add('despachosRecogidasDespachoRecogidaTipoRel',EntityType::class,[
                'required' => false,
                'class' => 'App\Entity\Transporte\TteDespachoRecogidaTipo',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('drt')
                        ->orderBy('drt.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Despacho recogida tipo:'
            ])
            ->add('nombre',TextType::class,['required' => true,'label' => 'Nombre:'])
            ->add('consecutivo',NumberType::class,['required' => true,'label' => 'Consecutivo:'])
            ->add('generaMonitoreo', CheckboxType::class, array('required'  => false))
            ->add('codigoComprobanteFk',NumberType::class,['required' => true])
            ->add('codigoCuentaFleteFk',NumberType::class,['required' => true])
            ->add('codigoCuentaRetencionFuenteFk',NumberType::class,['required' => true])
            ->add('codigoCuentaIndustriaComercioFk',NumberType::class,['required' => true])
            ->add('codigoCuentaSeguridadFk',NumberType::class,['required' => true])
            ->add('codigoCuentaEstampillaFk',NumberType::class,['required' => true])
            ->add('codigoCuentaPapeleriaFk',NumberType::class,['required' => true])
            ->add('codigoCuentaAnticipoFk',NumberType::class,['required' => true])
            ->add('codigoCuentaPagarFk',NumberType::class,['required' => true])
            ->add('guardar', SubmitType::class, ['label'=>'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']])
            ->add('guardarnuevo', SubmitType::class, ['label'=>'Guardar y nuevo','attr' => ['class' => 'btn btn-sm btn-primary']]);
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
