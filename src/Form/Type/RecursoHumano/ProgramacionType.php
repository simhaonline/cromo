<?php

namespace App\Form\Type\RecursoHumano;

use App\Entity\RecursoHumano\RhuGrupo;
use App\Entity\RecursoHumano\RhuPagoTipo;
use App\Entity\RecursoHumano\RhuProgramacion;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProgramacionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('grupoRel', EntityType::class, [
                'class' => RhuGrupo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('er')
                        ->orderBy('er.nombre');
                },
                'required' => true,
                'choice_label' => 'nombre',
                'attr' => ['class' => 'form-control']
            ])
            ->add('pagoTipoRel', EntityType::class, [
                'class' => RhuPagoTipo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('er')
                        ->orderBy('er.orden','ASC');
                },
                'required' => true,
                'choice_label' => 'nombre',
                'attr' => ['class' => 'form-control']
            ])
            ->add('fechaDesde', DateType::class, ['required' => true, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => ['class' => 'date',]])
            ->add('fechaHasta', DateType::class, ['required' => true, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => ['class' => 'date',]])
            ->add('fechaHastaPeriodo', DateType::class, ['required' => true, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => ['class' => 'date',]])
            ->add('nombre', TextType::class, ['required' => false,'attr' => ['placeholder' => 'Opcional']])
            ->add('mensajePago', TextareaType::class, ['required' => false])
            ->add('aplicarTransporte', CheckboxType::class, array('required' => false, 'data'=>true))

            ->add('guardar', SubmitType::class, ['label' => 'Guardar', 'attr' => ['class' => 'btn btn-sm btn-primary']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RhuProgramacion::class,
        ]);
    }

    public function getEstructuraPropiedadesLista()
    {
        $campos = '[
            {"campo":"codigoProgramacionPk",  "tipo":"pk"      ,"ayuda":"Codigo del registro"                       ,"titulo":"ID"},
            {"campo":"pagoTipoRel.nombre",    "tipo":"texto"   ,"ayuda":"Nombre del tipo de pago"                   ,"titulo":"TIPO" ,"relacion":""},
            {"campo":"nombre",                "tipo":"texto"   ,"ayuda":"Nombre dado a la programación"             ,"titulo":"NOMBRE"},
            {"campo":"grupoRel.nombre",       "tipo":"texto"   ,"ayuda":"Nombre del grupo"                          ,"titulo":"GRUPO","relacion":""},
            {"campo":"fechaDesde",            "tipo":"fecha"   ,"ayuda":"Fecha en que inicia el periodo"            ,"titulo":"DESDE"},
            {"campo":"fechaHasta",            "tipo":"fecha"   ,"ayuda":"Fecha en que termina el periodo"           ,"titulo":"HASTA"},
            {"campo":"dias",                  "tipo":"moneda"   ,"ayuda":"Numero de dias que compone el perdiodo"   ,"titulo":"DIAS"},
            {"campo":"cantidad",              "tipo":"moneda"   ,"ayuda":"Cantidad de registros en la programacion" ,"titulo":"#"},
            {"campo":"estadoAutorizado"                 ,"tipo":"bool"  ,"ayuda":"Estado autorizado"                            ,"titulo":"AUT"},
            {"campo":"estadoAprobado"                 ,"tipo":"bool"  ,"ayuda":"Estado aprobado"                            ,"titulo":"APR"},
            {"campo":"estadoContabilizado"                 ,"tipo":"bool"  ,"ayuda":"Estado contabilizado"                            ,"titulo":"CON"}
        ]';
        return $campos;
    }

    public function getEstructuraPropiedadesFiltro()
    {

        $campos = '[
            {"child":"codigoProgramacionPk", "tipo":"TextType",  "propiedades":{"label":"Codigo"}},
            {"child":"codigoPagoTipoFk",     "tipo":"EntityType","propiedades":{"class":"RhuPagoTipo","choice_label":"nombre","label":"Tipo"}},
            {"child":"nombre",               "tipo":"TextType",  "propiedades":{"label":"Nombre"}},
            {"child":"codigoGrupoFk",        "tipo":"EntityType","propiedades":{"class":"RhuGrupo","choice_label":"nombre","label":"Grupo"}},
            {"child":"fechaDesdeDesde",      "tipo":"DateType",  "propiedades":{"label":"Fecha Desde"}},
            {"child":"fechaHastaHasta",      "tipo":"DateType",  "propiedades":{"label":"Fecha Hasta"}}
        ]';

        return $campos;
    }
}