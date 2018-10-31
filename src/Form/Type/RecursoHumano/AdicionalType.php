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
            ->add('permanente',CheckboxType::class,['required' => false])
            ->add('aplicaDiaLaborado',CheckboxType::class,['required' => false])
            ->add('aplicaPrima',CheckboxType::class,['required' => false])
            ->add('aplicaCesantia',CheckboxType::class,['required' => false])
            ->add('detalle',TextType::class,['required' => false,'attr' => ['placeholder' => 'Opcional']])
            ->add('estadoInactivoPeriodo',CheckboxType::class,['required' => false])
            ->add('conceptoRel',EntityType::class,[
                'class' => RhuConcepto::class,
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('er')
                        ->orderBy('er.nombre','ASC');
                },'choice_label' => 'nombre',
                'required' => true
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
            {"campo":"codigoConceptoFk", "tipo":"texto" ,"ayuda":"Codigo del concepto" ,"titulo":"CONCEPTO"},
            {"campo":"detalle",          "tipo":"fecha" ,"ayuda":"Detalle del registro","titulo":"DETALLE"},
            {"campo":"codigoEmpleadoFk", "tipo":"texto" ,"ayuda":"Codigo del empleado" ,"titulo":"EMPLEADO"},
            {"campo":"codigoContratoFk", "tipo":"texto" ,"ayuda":"Codigo del contrato" ,"titulo":"CONTRATO"},
            {"campo":"fecha",            "tipo":"fecha" ,"ayuda":"Fecha"               ,"titulo":"FECHA"},
            {"campo":"vrValor",          "tipo":"moneda","ayuda":"Valor del anticipo"  ,"titulo":"VALOR"},
            {"campo":"permanente",       "tipo":"bool"  ,"ayuda":"Permanente"          ,"titulo":"PER"},
            {"campo":"estadoAutorizado", "tipo":"bool"  ,"ayuda":"Estado autorizado"   ,"titulo":"AUT"},                     
            {"campo":"estadoAprobado",   "tipo":"bool"  ,"ayuda":"Estado aprobado"     ,"titulo":"APR"},                     
            {"campo":"estadoAnulado",    "tipo":"bool"  ,"ayuda":"Estado anulado"      ,"titulo":"ANU"}
        ]';
        return $campos;
    }

    public function getEstructuraPropiedadesExportar()
    {
        $campos = '[
            {"campo":"codigoAdicionalPk","tipo":"pk"    ,"ayuda":"Codigo del registro" ,"titulo":"ID"},
            {"campo":"codigoConceptoFk", "tipo":"texto" ,"ayuda":"Codigo del concepto" ,"titulo":"CONCEPTO"},
            {"campo":"detalle",          "tipo":"fecha" ,"ayuda":"Detalle del registro","titulo":"DETALLE"},
            {"campo":"codigoEmpleadoFk", "tipo":"texto" ,"ayuda":"Codigo del empleado" ,"titulo":"EMPLEADO"},
            {"campo":"codigoContratoFk", "tipo":"texto" ,"ayuda":"Codigo del contrato" ,"titulo":"CONTRATO"},
            {"campo":"fecha",            "tipo":"fecha" ,"ayuda":"Fecha"               ,"titulo":"FECHA"},
            {"campo":"vrValor",          "tipo":"moneda","ayuda":"Valor del anticipo"  ,"titulo":"VALOR"},
            {"campo":"permanente",       "tipo":"bool"  ,"ayuda":"Permanente"          ,"titulo":"PER"},
            {"campo":"estadoAutorizado", "tipo":"bool"  ,"ayuda":"Estado autorizado"   ,"titulo":"AUT"},                     
            {"campo":"estadoAprobado",   "tipo":"bool"  ,"ayuda":"Estado aprobado"     ,"titulo":"APR"},                     
            {"campo":"estadoAnulado",    "tipo":"bool"  ,"ayuda":"Estado anulado"      ,"titulo":"ANU"}
        ]';
        return $campos;
    }
}
