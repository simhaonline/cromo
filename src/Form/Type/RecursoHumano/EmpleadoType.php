<?php

namespace App\Form\Type\RecursoHumano;

use App\Entity\RecursoHumano\RhuEmpleado;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class EmpleadoType extends AbstractType
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
            ->add('ciudadRel',EntityType::class,[
                'required' => false,
                'class' => 'App\Entity\General\GenCiudad',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('gc')
                        ->orderBy('gc.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Ciudad:'
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
            ->add('nombre1',TextType::class,['required' => true,'label' => 'Primer nombre:'])
            ->add('nombre2', TextType::class,['required' => true,'label' => 'Segundo nombre:'])
            ->add('apellido1', TextType::class,['required' => true,'label' => 'Primer apellido:'])
            ->add('apellido2', TextType::class,['required' => true,'label' => 'Segundo apellido:'])
            ->add('telefono',NumberType::class,['required' => true,'label' => 'Telefono:'])
            ->add('celular',NumberType::class,['required' => true,'label' => 'Celular:'])
            ->add('direccion',TextType::class,['required' => true,'label' => 'Direccion:'])
            ->add('fechaExpedicionIdentificacion', DateType::class,array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('barrio',TextType::class,['required' => true,'label' => 'Barrio:'])
            ->add('correo',TextType::class,['required' => true,'label' => 'Correo:'])
            ->add('fechaNacimiento', DateType::class,array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('cuenta',TextType::class,['required' => true,'label' => 'Cuenta:'])
            ->add('peso',TextType::class,['required' => true,'label' => 'Peso:'])
            ->add('estatura',TextType::class,['required' => true,'label' => 'Estatura:'])
            ->add('cargoDescripcion',TextType::class,['required' => true,'label' => 'Descripcion cargo:'])
            ->add('auxilioTransporte', CheckboxType::class, array('required'  => false))
            ->add('VrSalario',NumberType::class,['required' => true,'label' => 'Salario:'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RhuEmpleado::class,
        ]);
    }
}
