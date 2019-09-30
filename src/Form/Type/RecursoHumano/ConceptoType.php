<?php

namespace App\Form\Type\RecursoHumano;

use App\Entity\RecursoHumano\RhuConcepto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConceptoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoConceptoPk',TextType::class,['required'=> true,'label' => 'Codigo concepto:'])
            ->add('nombre',TextType::class,['required'=> true,'label' => 'Nombre:'])
            ->add('porcentaje',IntegerType::class,['label' => 'Porcentaje:','required' => true])
            ->add('operacion',ChoiceType::class,['required' => true,'choices' => ['SUMA' => 1,'RESTA' => -1,'NEUTRO' => 0],'label' => 'Operacion:'])
            ->add('numeroDian',TextType::class,['required' => false,'label' => 'Numero Dian (Cert. Ing):'])
            ->add('adicionalTipo',ChoiceType::class,['required' => true,'choices' => ['BONIFICACION' => 'BON','DESCUENTO' => 'DES','COMISION' => 'COM','NO APLICA' => 'NNN'],'label' => 'Adicional tipo:'])
            ->add('generaIngresoBasePrestacionVacacion',CheckboxType::class,['required' => false])
            ->add('generaIngresoBasePrestacion',CheckboxType::class,['required' => false])
            ->add('generaIngresoBaseCotizacion',CheckboxType::class,['required' => false])
            ->add('auxilioTransporte',CheckboxType::class,['required'=> false])
            ->add('incapacidad',CheckboxType::class,['required'=> false])
            ->add('incapacidadEntidad',CheckboxType::class,['required'=> false])
            ->add('pension',CheckboxType::class,['required'=> false])
            ->add('salud',CheckboxType::class,['required'=> false])
            ->add('vacacion',CheckboxType::class,['required'=> false])
            ->add('comision',CheckboxType::class,['required'=> false])
            ->add('cesantia',CheckboxType::class,['required'=> false])
            ->add('adicional',CheckboxType::class,['required'=> false])
            ->add('recargoNocturno',CheckboxType::class,['required'=> false])
            ->add('fondoSolidaridadPensional',CheckboxType::class,['required'=> false])
            ->add('guardar', SubmitType::class, ['attr' => ['class' => 'btn btn-sm btn-primary']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RhuConcepto::class,
        ]);
    }

    public function getEstructuraPropiedadesLista()
    {
        return '[
            {"campo":"codigoConceptoPk",            "tipo":"pk"     ,"ayuda":"Codigo del registro", "titulo":"ID"},
            {"campo":"nombre",                      "tipo":"texto"  ,"ayuda":"Nombre del registro", "titulo":"NOMBRE"},
            {"campo":"porcentaje",                  "tipo":"texto"  ,"ayuda":"Porcentaje",          "titulo":"%"},
            {"campo":"operacion",                   "tipo":"texto"  ,"ayuda":"Operación",           "titulo":"OP"},
            {"campo":"adicional",                   "tipo":"bool"  ,"ayuda":"Adicional",           "titulo":"ADI"},
            {"campo":"auxilioTransporte",          "tipo":"bool"  ,"ayuda":"Auxilio de transporte",           "titulo":"AUXT"},
            {"campo":"cesantia",                    "tipo":"bool"  ,"ayuda":"Cesantia",           "titulo":"CES"},
            {"campo":"comision",                    "tipo":"bool"  ,"ayuda":"Comision",           "titulo":"COM"},
            {"campo":"fondoSolidaridadPensional",   "tipo":"bool"  ,"ayuda":"Fondo solidaridad pensional",           "titulo":"FSP"},
            {"campo":"vacacion",                    "tipo":"bool"  ,"ayuda":"Vacación",           "titulo":"VAC"},
            {"campo":"salud",                       "tipo":"bool"  ,"ayuda":"Salud",           "titulo":"SAL"},
            {"campo":"recargoNocturno",             "tipo":"bool"  ,"ayuda":"Recargo nocturno",           "titulo":"REN"},
            {"campo":"pension",                     "tipo":"bool"  ,"ayuda":"Pensión",           "titulo":"PEN"},
            {"campo":"incapacidadEntidad",          "tipo":"bool"  ,"ayuda":"Incapacidad entidad",           "titulo":"IES"},
            {"campo":"generaIngresoBasePrestacion", "tipo":"bool"  ,"ayuda":"Ingreso base prestación",           "titulo":"IBP"},
            {"campo":"generaIngresoBaseCotizacion", "tipo":"bool"  ,"ayuda":"Ingreso base cotización",           "titulo":"IBC"}
        ]';
    }

    public function getEstructuraPropiedadesFiltro()
    {
        $campos = '[
            {"child":"codigoConceptoPk", "tipo":"TextType",   "propiedades":{"label":"Codigo"}},
            {"child":"nombre",            "tipo":"TextType",   "propiedades":{"label":"Nombre"}}

        ]';
        return $campos;
    }

    public function getEstructuraPropiedadesExportar()
    {
        return '[
            {"campo":"codigoConceptoPk",            "tipo":"pk"     ,"ayuda":"Codigo del registro",        "titulo":"ID"},
            {"campo":"nombre",                      "tipo":"texto"  ,"ayuda":"Nombre del registro",        "titulo":"NOMBRE"},
            {"campo":"porcentaje",                  "tipo":"texto"  ,"ayuda":"Porcentaje",                 "titulo":"%"},
            {"campo":"operacion",                   "tipo":"texto"  ,"ayuda":"Operación",                  "titulo":"OP"},
            {"campo":"adicional",                   "tipo":"texto"  ,"ayuda":"Adicional",                  "titulo":"ADI"},
            {"campo":"auxilioTransporte ",          "tipo":"texto"  ,"ayuda":"Auxilio de transporte",      "titulo":"AUXT"},
            {"campo":"cesantia",                    "tipo":"texto"  ,"ayuda":"Cesantia",                   "titulo":"CES"},
            {"campo":"comision",                    "tipo":"texto"  ,"ayuda":"Comision",                   "titulo":"COM"},
            {"campo":"fondoSolidaridadPensional",   "tipo":"texto"  ,"ayuda":"Fondo solidaridad pensional","titulo":"FSP"},
            {"campo":"vacacion",                    "tipo":"texto"  ,"ayuda":"Vacación",                   "titulo":"VAC"},
            {"campo":"salud",                       "tipo":"texto"  ,"ayuda":"Salud",                      "titulo":"SAL"},
            {"campo":"recargoNocturno",             "tipo":"texto"  ,"ayuda":"Recargo nocturno",           "titulo":"REN"},
            {"campo":"pension",                     "tipo":"texto"  ,"ayuda":"Pensión",                    "titulo":"PEN"},
            {"campo":"incapacidadEntidad",          "tipo":"texto"  ,"ayuda":"Incapacidad entidad",        "titulo":"IES"},
            {"campo":"generaIngresoBasePrestacion", "tipo":"texto"  ,"ayuda":"Ingreso base prestación",    "titulo":"IBP"},
            {"campo":"generaIngresoBaseCotizacion", "tipo":"texto"  ,"ayuda":"Ingreso base cotización",    "titulo":"IBC"}
        ]';
    }
}
