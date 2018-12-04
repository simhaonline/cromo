<?php

namespace App\Form\Type\RecursoHumano;

use App\Entity\General\GenCuenta;
use App\Entity\RecursoHumano\RhuEgreso;
use App\Entity\RecursoHumano\RhuEgresoTipo;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EgresoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fechaTrasmision', DateType::class, array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('fechaAplicacion', DateType::class, array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('nombre',TextType::class,[
                'required' => false,
                'attr' => ['placeholder' => 'Opcional']
            ])
            ->add('comentario',TextareaType::class,[
                'attr' => ['rows' => '6'],
                'required' => false
            ])
            ->add('egresoTipoRel',EntityType::class,[
                'class' => RhuEgresoTipo::class,
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('e')
                        ->orderBy('e.nombre');
                },'choice_label' => 'nombre',
                'required' => true,
                'attr' => ['class' => 'to-select-2']
            ])
            ->add('cuentaRel',EntityType::class,[
                'class' => GenCuenta::class,
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('e')
                        ->orderBy('e.nombre');
                },'choice_label' => 'nombre',
                'required' => true,
                'attr' => ['class' => 'to-select-2']
            ])
            ->add('guardar',SubmitType::class,['label' => 'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RhuEgreso::class,
        ]);
    }

    public function getEstructuraPropiedadesLista()
    {
        $campos = '[
            {"campo":"codigoEgresoPk",    "tipo":"pk",    "ayuda":"Codigo del registro",       "titulo":"ID"},
            {"campo":"codigoEgresoTipoFk","tipo":"texto", "ayuda":"Codigo del tipo de egreso", "titulo":"TIPO"},
            {"campo":"fecha",             "tipo":"fecha", "ayuda":"Fecha de registro",         "titulo":"FECHA"},
            {"campo":"numero",            "tipo":"texto", "ayuda":"Numero",                    "titulo":"NUMERO"},
            {"campo":"nombre",            "tipo":"texto", "ayuda":"Nombre del egreso",         "titulo":"NOMBRE"},
            {"campo":"codigoCuentaFk",    "tipo":"texto", "ayuda":"Codigo de la cuenta",       "titulo":"CUENTA"},
            {"campo":"estadoAutorizado",  "tipo":"bool",  "ayuda":"Estado autorizado",         "titulo":"AUT"},
            {"campo":"estadoAprobado",    "tipo":"bool",  "ayuda":"Estado aprobado",           "titulo":"APR"},
            {"campo":"estadoAnulado",     "tipo":"bool",  "ayuda":"Estado anulado",            "titulo":"ANU"}
        ]';
        return $campos;
    }

    public function getEstructuraPropiedadesFiltro()
    {

        $campos = '[
            {"child":"codigoEgresoPk",    "tipo":"TextType",  "propiedades":{"label":"Codigo"}},
            {"child":"codigoEgresoTipoFk","tipo":"EntityType","propiedades":{"class":"RhuEgresoTipo","choice_label":"nombre","label":"Tipo"}},
            {"child":"numero",            "tipo":"TextType",  "propiedades":{"label":"Numero"}},
            {"child":"fechaDesde",        "tipo":"DateType",  "propiedades":{"label":"Fecha Desde"}},
            {"child":"fechaHasta",        "tipo":"DateType",  "propiedades":{"label":"Fecha Hasta"}},
            {"child":"estadoAutorizado",  "tipo":"ChoiceType","propiedades":{"label":"Autorizado",     "choices":{"SI":true,"NO":false}}},
            {"child":"estadoAprobado",    "tipo":"ChoiceType","propiedades":{"label":"Aprobado",       "choices":{"SI":true,"NO":false}}},
            {"child":"estadoAnulado",     "tipo":"ChoiceType","propiedades":{"label":"Anulado",        "choices":{"SI":true,"NO":false}}}
        ]';

        return $campos;
    }
}