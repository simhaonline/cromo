<?php

namespace App\Form\Type\RecursoHumano;

use App\Entity\RecursoHumano\RhuSsPeriodo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SsPeriodoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('anio', TextType::class, array('required' => true))
            ->add('mes', TextType::class, array('required' => true))
            ->add('anioPago', TextType::class, array('required' => true))
            ->add('mesPago', TextType::class, array('required' => true))
            ->add('fechaDesde', DateType::class, array('format' => 'yyyyMMdd', 'data' => new \DateTime('now')))
            ->add('fechaHasta', DateType::class, array('format' => 'yyyyMMdd', 'data' => new \DateTime('now')))
            ->add('fechaPago', DateType::class, array('format' => 'yyyyMMdd', 'data' => new \DateTime('now')))
            ->add('guardar',SubmitType::class,['label' => 'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RhuSsPeriodo::class,
        ]);
    }

    public function getEstructuraPropiedadesLista()
    {
        $campos = '[
            {"campo":"codigoSsPeriodoPk",    "tipo":"pk",    "ayuda":"Codigo del registro",       "titulo":"ID"},
            {"campo":"anio",        "tipo":"texto", "ayuda":"Año del periodo", "titulo":"AÑO"},
            {"campo":"anioPago",        "tipo":"texto", "ayuda":"Año del periodo", "titulo":"AÑO PAGO"},
            {"campo":"mes",        "tipo":"texto", "ayuda":"Año del periodo", "titulo":"MES"},
            {"campo":"mesPago",        "tipo":"texto", "ayuda":"Año del periodo", "titulo":"MES PAGO"},
            {"campo":"fechaDesde",        "tipo":"fecha"   ,"ayuda":"Fecha desde" ,"titulo":"DESDE"},
            {"campo":"fechaHasta",        "tipo":"fecha"   ,"ayuda":"Fecha hasta" ,"titulo":"HASTA"},
            {"campo":"fechaPago",        "tipo":"fecha"   ,"ayuda":"Fecha hasta" ,"titulo":"FECHA PAGO"},
            {"campo":"estadoGenerado",          "tipo":"bool",  "ayuda":"Estado generado",           "titulo":"GENERADO"},
            {"campo":"estadoCerrado",          "tipo":"bool",  "ayuda":"Estado cerrado",           "titulo":"CERRADO"}
        ]';
        return $campos;
    }

    public function getEstructuraPropiedadesFiltro()
    {

        $campos = '[
            {"child":"codigoSsPeriodoPk",    "tipo":"TextType",  "propiedades":{"label":"Codigo"}}
        ]';

        return $campos;
    }
}