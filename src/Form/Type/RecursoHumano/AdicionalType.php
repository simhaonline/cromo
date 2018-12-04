<?php

namespace App\Form\Type\RecursoHumano;

use App\Entity\RecursoHumano\RhuAdicional;
use App\Entity\RecursoHumano\RhuConcepto;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdicionalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoEmpleadoFk', TextType::class, ['required' => true])
            ->add('vrValor', NumberType::class, ['required' => true])
            ->add('aplicaDiaLaborado',CheckboxType::class,['required' => false])
            ->add('aplicaNomina',CheckboxType::class,['required' => false])
            ->add('aplicaPrima',CheckboxType::class,['required' => false])
            ->add('aplicaCesantia',CheckboxType::class,['required' => false])
            ->add('detalle',TextType::class,['required' => false,'attr' => ['placeholder' => 'Opcional']])
            ->add('estadoInactivoPeriodo',CheckboxType::class,['required' => false])
            ->add('conceptoRel',EntityType::class,[
                'class' => RhuConcepto::class,
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('er')
                        ->where('er.adicional = 1')
                        ->orderBy('er.nombre','ASC');
                },'choice_label' => 'nombre',
                'required' => true,
                'attr' => ['class' => 'form-control to-select-2']
            ])
            ->add('guardar',SubmitType::class,['label' => 'guardar','attr' => ['class' => 'btn btn-sm btn-primary']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RhuAdicional::class,
        ]);
    }

    public function getEstructuraPropiedadesLista()
    {
        $campos = '[
            {"campo":"codigoAdicionalPk","tipo":"pk"    ,"ayuda":"Codigo del registro" ,"titulo":"ID"},
            {"campo":"codigoConceptoFk", "tipo":"texto" ,"ayuda":"Codigo del concepto" ,"titulo":"CODIGO"},
            {"campo":"conceptoRel.nombre", "tipo":"texto" ,"ayuda":"Codigo del concepto" ,"titulo":"CONCEPTO", "relacion":"SI"},
            {"campo":"empleadoRel.numeroIdentificacion","tipo":"texto"  ,"ayuda":"Numero de identificacion del empleado" ,"titulo":"IDENTIFICACION" ,"relacion":"SI"},
            {"campo":"empleadoRel.nombreCorto"         ,"tipo":"texto"  ,"ayuda":"Nombre del empleado"                   ,"titulo":"EMPLEADO"         ,"relacion":"SI"},             
            {"campo":"detalle",          "tipo":"texto" ,"ayuda":"Detalle del registro","titulo":"DETALLE"},
            {"campo":"fecha",            "tipo":"fecha" ,"ayuda":"Fecha"               ,"titulo":"FECHA"},
            {"campo":"vrValor",          "tipo":"moneda","ayuda":"Valor del anticipo"  ,"titulo":"VALOR"},
            {"campo":"permanente",       "tipo":"bool"  ,"ayuda":"Permanente"          ,"titulo":"PER"},
            {"campo":"aplicaNomina", "tipo":"bool"  ,"ayuda":"Estado autorizado"   ,"titulo":"NOM"},                     
            {"campo":"aplicaPrima",   "tipo":"bool"  ,"ayuda":"Estado aprobado"     ,"titulo":"PRI"},                     
            {"campo":"estadoInactivo",    "tipo":"bool"  ,"ayuda":"Estado anulado"      ,"titulo":"INA"}
        ]';
        return $campos;
    }

    public function getEstructuraPropiedadesFiltro()
    {
        $campos = '[
            {"child":"codigoAdicionalPk", "tipo":"TextType",   "propiedades":{"label":"Codigo"}},
            {"child":"codigoConceptoFk",  "tipo":"EntityType", "propiedades":{"class":"RhuConcepto","choice_label":"nombre","label":"Concepto"}},
            {"child":"codigoEmpleadoFk",  "tipo":"TextType",   "propiedades":{"label":"Empleado"}},
            {"child":"fechaDesde",        "tipo":"DateType",   "propiedades":{"label":"Fecha Desde"}},
            {"child":"fechaHasta",        "tipo":"DateType",   "propiedades":{"label":"Fecha Hasta"}},
            {"child":"estadoInactivo",    "tipo":"ChoiceType", "propiedades":{"label":"Autorizado",     "choices":{"SI":true,"NO":false}}}
        ]';

        return $campos;
    }
}
