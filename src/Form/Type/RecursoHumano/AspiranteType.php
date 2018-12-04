<?php

namespace App\Form\Type\RecursoHumano;

use App\Entity\RecursoHumano\RhuAspirante;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AspiranteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('identificacionRel',EntityType::class,[
                'required' => false,
                'class' => 'App\Entity\General\GenIdentificacion',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('i')
                        ->orderBy('i.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Identificacion tipo:'
            ])
            ->add('ciudadExpedicionRel',EntityType::class,[
                'required' => false,
                'class' => 'App\Entity\General\GenCiudad',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('ce')
                        ->orderBy('ce.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Ciudad residencia:'
            ])
            ->add('sexoRel',EntityType::class,[
                'required' => false,
                'class' => 'App\Entity\General\GenSexo',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('g')
                        ->orderBy('g.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Sexo:'
            ])
            ->add('rhRel',EntityType::class,[
                'required' => false,
                'class' => 'App\Entity\RecursoHumano\RhuRh',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('rh')
                        ->orderBy('rh.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Rh:'
            ])
            ->add('ciudadNacimientoRel',EntityType::class,[
                'required' => false,
                'class' => 'App\Entity\General\GenCiudad',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('ce')
                        ->orderBy('ce.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Ciudad residencia:'
            ])
            ->add('estadoCivilRel',EntityType::class,[
                'required' => false,
                'class' => 'App\Entity\General\GenEstadoCivil',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('ec')
                        ->orderBy('ec.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Estado civil:'
            ])
            ->add('cargoRel',EntityType::class,[
                'required' => false,
                'class' => 'App\Entity\RecursoHumano\RhuCargo',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('ac')
                        ->orderBy('ac.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Cargo interno:'
            ])
            ->add('numeroIdentificacion',NumberType::class,['required' => true,'label' => 'numero identificacion:'])
            ->add('libretaMilitar',TextType::class,['required' => true,'label' => 'Libreta militar:'])
            ->add('nombre1',TextType::class,['required' => true,'label' => 'Primer nombre:'])
            ->add('nombre2',TextType::class,['required' => true,'label' => 'Segundo nombre:'])
            ->add('apellido1',TextType::class,['required' => true,'label' => 'Primer apellido:'])
            ->add('apellido2',TextType::class,['required' => true,'label' => 'Segundo apellido:'])
            ->add('telefono',NumberType::class,['required' => true,'label' => 'Telefono:'])
            ->add('celular',NumberType::class,['required' => true,'label' => 'Celular:'])
            ->add('direccion',TextType::class,['required' => true,'label' => 'Direccion:'])
            ->add('barrio',TextType::class,['required' => true,'label' => 'Barrio:'])
            ->add('correo',TextType::class,['required' => true,'label' => 'Correo:'])
            ->add('fechaNacimiento', DateType::class,array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('peso',TextType::class,['required' => true,'label' => 'Peso:'])
            ->add('estatura',TextType::class,['required' => true,'label' => 'Estatura:'])
            ->add('cargoAspira',TextType::class,['required' => true,'label' => 'Cargo aspira:'])
            ->add('recomendado',TextType::class,['required' => true,'label' => 'Recomendado:'])
            ->add('reintegro', CheckboxType::class, array('required'  => false))
            ->add('guardar', SubmitType::class, ['label'=>'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']])
            ->add('guardarnuevo', SubmitType::class, ['label'=>'Guardar y nuevo','attr' => ['class' => 'btn btn-sm btn-primary']]);;
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RhuAspirante::class,
        ]);
    }

    public function getEstructuraPropiedadesLista()
    {
        $campos = '[
            {"campo":"codigoAspirantePk",      "tipo":"pk"      ,"ayuda":"Codigo del empleado"                   ,"titulo":"ID"},
            {"campo":"numeroIdentificacion",  "tipo":"texto"   ,"ayuda":"Numero de identificacion del empleado" ,"titulo":"IDENTIFICACION"},
            {"campo":"nombreCorto",           "tipo":"texto"   ,"ayuda":"Nombre del empleado"                   ,"titulo":"NOMBRE"},
            {"campo":"telefono",              "tipo":"texto"   ,"ayuda":"Telefono del empleado"                 ,"titulo":"TELEFONO"},
            {"campo":"correo",                "tipo":"texto"   ,"ayuda":"Correo del empleado"                   ,"titulo":"CORREO"},
            {"campo":"direccion",             "tipo":"texto"   ,"ayuda":"Direccion de residencia del empleado"  ,"titulo":"DIRECCION"},                     
            {"campo":"estadoContrato",        "tipo":"bool"    ,"ayuda":"Posee contrato activo"                 ,"titulo":"E_C"}                     
        ]';
        return $campos;
    }

    public function getEstructuraPropiedadesFiltro()
    {
        $campos = '[
            {"child":"codigoAspirantePk", "tipo":"TextType",   "propiedades":{"label":"Codigo"}},
            {"child":"nombreCorto",       "tipo":"EntityType", "propiedades":{"class":"RhuEgresoTipo","choice_label":"nombre","label":"Tipo"}},
            {"child":"numero",            "tipo":"TextType",   "propiedades":{"label":"Numero"}},
            {"child":"estadoAutorizado",  "tipo":"ChoiceType", "propiedades":{"label":"Autorizado",     "choices":{"SI":true,"NO":false}}},
            {"child":"estadoAprobado",    "tipo":"ChoiceType", "propiedades":{"label":"Aprobado",       "choices":{"SI":true,"NO":false}}},
            {"child":"estadoCerrado",     "tipo":"ChoiceType", "propiedades":{"label":"Cerrado",        "choices":{"SI":true,"NO":false}}}
        ]';
        return $campos;
    }
}
