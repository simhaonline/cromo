<?php

namespace App\Form\Type\Inventario;

use App\Entity\Inventario\InvFacturaTipo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class FacturaTipoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoFacturaTipoPk',TextType::class,['label' => 'Codigo factura tipo:','required' => true])
            ->add('nombre',TextType::class,['label' => 'Nombre:','required' => true])
            ->add('consecutivo',TextType::class,['label' => 'Consecutivo: '])
            ->add('prefijo',TextType::class,['label' => 'Prefijo: ', 'required' => false])
            ->add('fechaDesdeVigencia', DateType::class, array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('fechaHastaVigencia', DateType::class, array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('numeracionDesde',TextType::class,['label' => 'Numeracion desde: '])
            ->add('numeracionHasta',TextType::class,['label' => 'Numeracion hasta: '])
            ->add('numeroResolucionDianFactura',TextareaType::class,['label' => 'Resolucion DIAN: '])
            ->add('informacionCuentaPago',TextareaType::class,['label' => 'Informacion cuenta de pago: '])
            ->add('guardar', SubmitType::class, ['label'=>'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => InvFacturaTipo::class,
        ]);
    }

    public function getEstructuraPropiedadesLista()
    {
        $campos = '[
            {"campo":"codigoFacturaTipoPk",         "tipo":"pk"     ,"ayuda":"Codigo del registro",           "titulo":"ID"},
            {"campo":"nombre",                      "tipo":"texto"  ,"ayuda":"Nombre de la factura tipo",     "titulo":"NOMBRE"},
            {"campo":"consecutivo",                 "tipo":"texto"  ,"ayuda":"Consecutivo de la factura",     "titulo":"CONSECUTIVO"},
            {"campo":"prefijo",                     "tipo":"texto"  ,"ayuda":"Prefijo de facturacion",     "titulo":"PREFIJO"},
            {"campo":"fechaDesdeVigencia",          "tipo":"fecha"  ,"ayuda":"Fecha desde que inicia la vigencia de la resolucion",     "titulo":"FECHA DESDE"},
            {"campo":"fechaHastaVigencia",          "tipo":"fecha"  ,"ayuda":"Fecha hasta que finaliza la vigencia de la resolucion",     "titulo":"FECHA_HASTA"},
            {"campo":"numeracionDesde",             "tipo":"texto"  ,"ayuda":"Numero en que inicia la resolucion",     "titulo":"NUMERACION DESDE"},
            {"campo":"numeracionHasta",             "tipo":"texto"  ,"ayuda":"Numero en que finaliza la resolucion",     "titulo":"NUMERACION HASTA"},
            {"campo":"numeroResolucionDianFactura",             "tipo":"texto"  ,"ayuda":"Numero de la resolucion",     "titulo":"NUMERACION RESOLUCION"},
            {"campo":"informacionCuentaPago",             "tipo":"texto"  ,"ayuda":"Informacion de la cuenta de pago",     "titulo":"INFORMACION CUENTA PAGO"}                            
                                                                          
        ]';
        return $campos;
    }

    public function getEstructuraPropiedadesExportar()
    {
        $campos = '[
            {"campo":"codigoFacturaTipoPk",         "tipo":"pk"     ,"ayuda":"Codigo del registro",           "titulo":"ID"},
            {"campo":"nombre",                      "tipo":"texto"  ,"ayuda":"Nombre de la factura tipo",     "titulo":"NOMBRE"},
            {"campo":"consecutivo",                 "tipo":"texto"  ,"ayuda":"Consecutivo de la factura",     "titulo":"CONSECUTIVO"},
            {"campo":"prefijo",                     "tipo":"texto"  ,"ayuda":"Prefijo de facturacion",     "titulo":"PREFIJO"},
            {"campo":"fechaDesdeVigencia",          "tipo":"fecha"  ,"ayuda":"Fecha desde que inicia la vigencia de la resolucion",     "titulo":"FECHA DESDE"},
            {"campo":"fechaHastaVigencia",          "tipo":"fecha"  ,"ayuda":"Fecha hasta que finaliza la vigencia de la resolucion",     "titulo":"FECHA_HASTA"},
            {"campo":"numeracionDesde",             "tipo":"texto"  ,"ayuda":"Numero en que inicia la resolucion",     "titulo":"NUMERACION DESDE"},
            {"campo":"numeracionHasta",             "tipo":"texto"  ,"ayuda":"Numero en que finaliza la resolucion",     "titulo":"NUMERACION HASTA"},
            {"campo":"numeroResolucionDianFactura",             "tipo":"texto"  ,"ayuda":"Numero de la resolucion",     "titulo":"NUMERACION RESOLUCION"},
            {"campo":"informacionCuentaPago",             "tipo":"texto"  ,"ayuda":"Informacion de la cuenta de pago",     "titulo":"INFORMACION CUENTA PAGO"}                                         
        ]';
        return $campos;
    }
}
