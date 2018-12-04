<?php

namespace App\Form\Type\RecursoHumano;

use App\Entity\RecursoHumano\RhuEmpleado;
use App\Entity\RecursoHumano\RhuNovedad;
use App\Entity\RecursoHumano\RhuNovedadTipo;
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

class NovedadType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoEmpleadoFk', TextType::class, ['required' => true])
            ->add('fechaDesde', DateType::class, array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('fechaHasta', DateType::class, array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('prorroga', CheckboxType::class, ['required' => false, 'label' => ' '])
            ->add('transcripcion', CheckboxType::class, ['required' => false, 'label' => ' '])
            ->add('vrIbcPropuesto', NumberType::class, ['required' => false])
            ->add('vrPropuesto', NumberType::class, ['required' => false])
            ->add('comentarios', TextareaType::class, ['required' => false])
            ->add('novedadTipoRel', EntityType::class, [
                'class' => RhuNovedadTipo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('er')
                        ->orderBy('er.nombre', 'ASC');
                },
                'choice_label' => function($er){
                    $tipo = $er->getSubTipo() == 'L' ? 'LICENCIA' : 'INCAPACIDAD';
                    return $er->getNombre().' - '.$tipo;
                },
                'required' => true
            ])
            ->add('guardar', SubmitType::class, ['attr' => ['class' => 'btn btn-sm btn-primary']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RhuNovedad::class,
        ]);
    }

    public function getEstructuraPropiedadesLista()
    {
        $campos = '[
        {"campo":"codigoNovedadPk",                 "tipo":"pk"    ,"ayuda":"Codigo del registro"        ,"titulo":"ID"},
        {"campo":"novedadTipoRel.nombre",           "tipo":"texto" ,"ayuda":"Nombre del tipo de novedad" ,"titulo":"TIPO", "relacion":""},
        {"campo":"fecha",                           "tipo":"fecha" ,"ayuda":"Fecha del registro"         ,"titulo":"FECHA"},
        {"campo":"codigoEmpleadoFk",                "tipo":"texto" ,"ayuda":"Codigo del empleado"        ,"titulo":"EMPLEADO"},
        {"campo":"empleadoRel.nombreCorto",         "tipo":"texto" ,"ayuda":"Nombre del empleado"        ,"titulo":"NOMBRE", "relacion":""},
        {"campo":"empleadoRel.numeroIdentificacion","tipo":"texto" ,"ayuda":"Identificacion del empleado","titulo":"IDENTIFICACION", "relacion":""},
        {"campo":"codigoContratoFk",                "tipo":"texto" ,"ayuda":"Codigo del contrato"        ,"titulo":"CONTRATO"},
        {"campo":"fechaDesde",                      "tipo":"fecha" ,"ayuda":"Fecha desde"                ,"titulo":"DESDE"},                     
        {"campo":"fechaHasta",                      "tipo":"fecha" ,"ayuda":"Fecha hasta"                ,"titulo":"HASTA"}]';
        return $campos;
    }

    public function getEstructuraPropiedadesFiltro()
    {
        $campos = '[
            {"child":"codigoNovedadPk",     "tipo":"TextType",   "propiedades":{"label":"Codigo"}},
            {"child":"codigoNovedadTipoFk", "tipo":"EntityType", "propiedades":{"class":"RhuNovedadTipo","choice_label":"nombre","label":"Novedad tipo"}},
            {"child":"codigoEmpleadoFk",    "tipo":"TextType",   "propiedades":{"label":"Empleado"}},
            {"child":"fechaDesde",          "tipo":"DateType",   "propiedades":{"label":"Fecha Desde"}},
            {"child":"fechaHasta",          "tipo":"DateType",   "propiedades":{"label":"Fecha Hasta"}}
        ]';

        return $campos;
    }

}
