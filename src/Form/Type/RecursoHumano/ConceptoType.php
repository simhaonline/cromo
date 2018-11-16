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
            ->add('generaIngresoBasePrestacion',CheckboxType::class,['required' => false,'label' => 'Genera IBP'])
            ->add('generaIngresoBaseCotizacion',CheckboxType::class,['required' => false,'label' => 'Genera IBC'])
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

    public function getEstructuraPropiedadesLista()
    {
        return '[
            {"campo":"codigoConceptoPk",            "tipo":"pk"     ,"ayuda":"Codigo del registro",  "titulo":"ID"},
            {"campo":"nombre",                      "tipo":"texto"  ,"ayuda":"Nombre del registro",  "titulo":"NOMBRE"},                                  
            {"campo":"porcentaje",                  "tipo":"texto"  ,"ayuda":"Nombre del registro",  "titulo":"NOMBRE"},                                  
            {"campo":"operacion",                   "tipo":"texto"  ,"ayuda":"Nombre del registro",  "titulo":"NOMBRE"},                                  
            {"campo":"numeroDian",                  "tipo":"texto"  ,"ayuda":"Nombre del registro",  "titulo":"NOMBRE"},                                  
            {"campo":"adicionalTipo",               "tipo":"texto"  ,"ayuda":"Nombre del registro",  "titulo":"NOMBRE"},                                  
            {"campo":"generaIngresoBasePrestacion", "tipo":"bool"   ,"ayuda":"Nombre del registro",   "titulo":"NOMBRE"},                                  
            {"campo":"generaIngresoBaseCotizacion", "tipo":"bool"   ,"ayuda":"Nombre del registro",   "titulo":"NOMBRE"},                                  
            {"campo":"incapacidad",                 "tipo":"bool"   ,"ayuda":"Nombre del registro",   "titulo":"NOMBRE"},                                  
            {"campo":"incapacidadEntidad",          "tipo":"bool"   ,"ayuda":"Nombre del registro",   "titulo":"NOMBRE"},                                  
            {"campo":"pension",                     "tipo":"bool"   ,"ayuda":"Nombre del registro",   "titulo":"NOMBRE"},                                  
            {"campo":"salud",                       "tipo":"bool"   ,"ayuda":"Nombre del registro",   "titulo":"NOMBRE"},                                  
            {"campo":"vacacion",                    "tipo":"bool"   ,"ayuda":"Nombre del registro",   "titulo":"NOMBRE"},                                  
            {"campo":"comision",                    "tipo":"bool"   ,"ayuda":"Nombre del registro",   "titulo":"NOMBRE"},                                  
            {"campo":"cesantia",                    "tipo":"bool"   ,"ayuda":"Nombre del registro",   "titulo":"NOMBRE"},                                  
            {"campo":"adicional",                   "tipo":"bool"   ,"ayuda":"Nombre del registro",   "titulo":"NOMBRE"},                                  
            {"campo":"recargoNocturno",             "tipo":"bool"   ,"ayuda":"Nombre del registro",   "titulo":"NOMBRE"},                                  
            {"campo":"fondoSolidaridadPensional",   "tipo":"bool"   ,"ayuda":"Nombre del registro",   "titulo":"NOMBRE"},                                  
        ]';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RhuConcepto::class,
        ]);
    }
}
