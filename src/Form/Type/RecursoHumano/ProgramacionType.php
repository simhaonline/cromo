<?php

namespace App\Form\Type\RecursoHumano;

use App\Entity\RecursoHumano\RhuGrupo;
use App\Entity\RecursoHumano\RhuProgramacion;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProgramacionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('grupoRel',EntityType::class,[
                'class' => RhuGrupo::class,
                'query_builder' => function (EntityRepository $er){
                    return $er->createQueryBuilder('er')
                        ->orderBy('er.nombre');
                },
                'required' => true,
                'choice_label' => 'nombre',
                'attr' => ['class' => 'form-control']
            ])
            ->add('fechaDesde',DateType::class,['data' => new \DateTime('now')])
            ->add('fechaHasta',DateType::class,['data' => new \DateTime('now')])
            ->add('nombre',TextType::class,['required' => false])
            ->add('dias',IntegerType::class)
            ->add('guardar',SubmitType::class,['label' => 'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']]);
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
            {"campo":"codigoProgramacionPk",  "tipo":"pk"      ,"ayuda":"Codigo del registro"                    ,"titulo":"ID"},
            {"campo":"codigoGrupoFk",         "tipo":"texto"   ,"ayuda":"Nombre del grupo"                       ,"titulo":"GRUPO"},
            {"campo":"fechaDesde",            "tipo":"fecha"   ,"ayuda":"Fecha en que inicia el periodo"         ,"titulo":"DESDE"},
            {"campo":"fechaHasta",            "tipo":"fecha"   ,"ayuda":"Fecha en que termina el periodo"        ,"titulo":"HASTA"},
            {"campo":"nombre",                "tipo":"texto"   ,"ayuda":"Nombre de la programacion"              ,"titulo":"NOMBRE"},
            {"campo":"dias",                  "tipo":"texto"   ,"ayuda":"Numero de dias que compone el perdiodo" ,"titulo":"DIAS"}                     
        ]';
        return $campos;
    }
}