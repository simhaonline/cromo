<?php

namespace App\Form\Type\Transporte;

use App\Entity\Transporte\TteConductor;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ConductorType extends AbstractType
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
                'label' => 'Tipo identificacion:'
            ])
            ->add('ciudadRel',EntityType::class,[
                'required' => false,
                'class' => 'App\Entity\Transporte\TteCiudad',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Ciudad:'
            ])
            ->add('numeroIdentificacion',NumberType::class,['required' => true,'label' => 'Numero identificacion:'])
            ->add('nombre1',TextType::class,['required' => true,'label' => 'Primer nombre:'])
            ->add('nombre2',TextType::class,['required' => false,'label' => 'Segundo nombre:'])
            ->add('apellido1',TextType::class,['required' => true,'label' => 'Primer apellido:'])
            ->add('apellido2',TextType::class,['required' => false,'label' => 'Segundo apellido:'])
            ->add('rhRel',EntityType::class,[
                'required' => false,
                'class' => 'App\Entity\RecursoHumano\RhuRh',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('r')
                        ->orderBy('r.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Rh:'
            ])
            ->add('correo', TextType::class, array('required' => false))
            ->add('codigoActividadEconomicaFk', TextType::class, array('required' => false))
            ->add('direccion',TextType::class,['required' => true,'label' => 'Direccion:'])
            ->add('arl',TextType::class,['required' => true,'label' => 'Arl:'])
            ->add('fechaVenceArl', DateType::class,array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('fechaNacimiento', DateType::class,array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('telefono',TextType::class,['required' => true,'label' => 'Telefono:'])
            ->add('movil',TextType::class,['required' => true,'label' => 'Celular:'])
            ->add('numeroLicencia',NumberType::class,['required' => true,'label' => 'Licencia:'])
            ->add('categoriaLicencia',TextType::class,['required' => true,'label' => 'Categoria:'])
            ->add('fechaVenceLicencia', DateType::class,array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('barrio',TextType::class,['required' => true,'label' => 'Barrio:'])
            ->add('alias',TextType::class,['required' => false,'label' => 'Alias:'])
            ->add('codigoVehiculo',TextType::class,['required' => false,'label' => 'Codigo vehiculo:'])
            ->add('estadoInactivo', CheckboxType::class, ['required' => false,'label' => 'Inactivo'])
            ->add('comentario',TextareaType::class,['required' => false,'label' => 'Comentarios:'])
            ->add('guardar', SubmitType::class, ['label'=>'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']])
            ->add('guardarnuevo', SubmitType::class, ['label'=>'Guardar y nuevo','attr' => ['class' => 'btn btn-sm btn-primary']]);;
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TteConductor::class,
        ]);
    }

    public function getEstructuraPropiedadesFiltro()
    {
        $campos = '[	
            {"child":"nombreCorto",                     "tipo":"TextType",   "propiedades":{"label":"Nombre"},      "operador":"like"},	
            {"child":"numeroIdentificacion",            "tipo":"TextType",   "propiedades":{"label":"Identificación"},      "operador":"="}	
        ]';
        return $campos;
    }
}

