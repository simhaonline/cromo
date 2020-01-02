<?php

namespace App\Form\Type\Transporte;

use App\Entity\Transporte\TteFacturaTipo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class FacturaTipoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoFacturaTipoPk',TextType::class,['required' => true,'label' => 'Codigo factura:'])
            ->add('nombre',TextType::class,['required' => true,'label' => 'Nombre:'])
            ->add('consecutivo',NumberType::class,['required' => true,'label' => 'Consecutivo:'])
            ->add('prefijo',TextType::class,['required' => false,'label' => 'Prefijo:'])
            ->add('resolucionFacturacion',TextType::class,['required' => false,'label' => 'Resolucion:'])
            ->add('guiaFactura', CheckboxType::class, array('required'  => false,'label'=>'Guia factura'))
            ->add('codigoCuentaCobrarTipoFk',TextType::class,['required' => false,'label' => 'Codigo cuenta cobrar tipo fk:'])
            ->add('codigoFacturaClaseFk',TextType::class,['required' => false,'label' => 'Codigo factura clase fk:'])
            ->add('codigoCuentaClienteFk',TextType::class,['required' => false,'label' => 'Codigo cuenta cliente fk:'])
            ->add('codigoCuentaIngresoFleteIntermediacionFk',TextType::class,['required' => false,'label' => 'Codigo cuenta ingreso flete (Intermediacion) :'])
            ->add('codigoComprobanteFk',TextType::class,['required' => false,'label' => 'Codigo comprobante:'])
            ->add('guardar', SubmitType::class, ['label'=>'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TteFacturaTipo::class,
        ]);
    }

    public function getEstructuraPropiedadesLista(){
        return '[	
            {"campo":"codigoFacturaTipoPk",         "tipo":"pk",        "ayuda":"Codigo del registro",     "titulo":"ID"},	
            {"campo":"nombre",                      "tipo":"texto",     "ayuda":"Nombre del registro",     "titulo":"NOMBRE"},	
            {"campo":"prefijo",                     "tipo":"texto",     "ayuda":"Prefijo",                 "titulo":"PRE"},	
            {"campo":"consecutivo",                 "tipo":"numero",    "ayuda":"Consecutivo",             "titulo":"CON"},	
            {"campo":"guiaFactura",                 "tipo":"bool",      "ayuda":"Guia factura",            "titulo":"GF"},            	
            {"campo":"codigoFacturaClaseFk",        "tipo":"texto",     "ayuda":"Codigo factura clase",    "titulo":"CLASE"},	
            {"campo":"codigoComprobanteFk",        "tipo":"texto",     "ayuda":"Comprobante contabilidad",    "titulo":"COMPROBANTE"},	
            {"campo":"codigoCuentaClienteFk",        "tipo":"texto",     "ayuda":"Cuenta cliente",    "titulo":"CTA CLIENTE"}	
            	
        ]';
    }
    public function getEstructuraPropiedadesExportar(){
        return '[	
            {"{"campo":"codigoFacturaTipoPk",         "tipo":"pk",        "ayuda":"Codigo del registro",     "titulo":"ID"},	
            {"campo":"nombre",                      "tipo":"texto",     "ayuda":"Nombre del registro",     "titulo":"NOMBRE"},	
            {"campo":"consecutivo",                 "tipo":"numero",    "ayuda":"Consecutivo",             "titulo":"CONSECUTIVO"},	
            {"campo":"resolucionFacturacion",       "tipo":"texto",     "ayuda":"Resolucion factura",      "titulo":"RESOLUCION FACTURA"},	
            {"campo":"guiaFactura",                 "tipo":"bool",      "ayuda":"Guia factura",            "titulo":"GUIA FACTURA"},	
            {"campo":"prefijo",                     "tipo":"texto",     "ayuda":"Prefijo",                 "titulo":"PREFIJO"},	
            {"campo":"codigoFacturaClaseFk",        "tipo":"texto",     "ayuda":"Codigo factura clase",    "titulo":"CODIGO FACTURA CLASE"},	
            {"campo":"codigoCuentaCobrarTipoFk",    "tipo":"texto",     "ayuda":"Codigo cuenta cobro tipo","titulo":"CODIGO CUENTA COBRO TIPO"},	
            {"campo":"codigoCuentaIngresoFleteFk",  "tipo":"texto",     "ayuda":"Codigo cuenta ingreso flete","titulo":"CODIGO CUENTA INGRESO TIPO"},	
            {"campo":"codigoCuentaIngresoManejoFk", "tipo":"texto",     "ayuda":"Codigo cuenta ingreso manejo","titulo":"CODIGO CUENTA INGRESO MANEJO"},	
            {"campo":"naturalezaCuentaIngreso",     "tipo":"texto",     "ayuda":"Naturaleza cuenta ingreso","titulo":"NATURALEZA CUENTA INGRESO"},	
            {"campo":"codigoCuentaClienteFk",       "tipo":"texto",     "ayuda":"Codigo cuenta cliente",    "titulo":"CODIGO CUENTA CLIENTE"},	
            {"campo":"naturalezaCuentaCliente",     "tipo":"texto",     "ayuda":"Naturaleza cuenta cliente","titulo":"NATURALEZA CUENTA CLIENTE"},	
            {"campo":"codigoComprobanteFk",         "tipo":"texto",     "ayuda":"Codigo comprobante",       "titulo":"CODIGO COMPROBANTE"}	
        ]';
    }
}

