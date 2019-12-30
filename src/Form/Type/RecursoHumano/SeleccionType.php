<?php

namespace App\Form\Type\RecursoHumano;

use App\Entity\RecursoHumano\RhuSeleccion;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SeleccionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('seleccionTipoRel',EntityType::class,[
                'required' => false,
                'class' => 'App\Entity\RecursoHumano\RhuSeleccionTipo',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('st')
                        ->orderBy('st.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Seleccion tipo:'
            ])
            ->add('solicitudRel',EntityType::class,[
                'required' => false,
                'class' => 'App\Entity\RecursoHumano\RhuSolicitud',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('slt')
                        ->orderBy('slt.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Solicitud:'
            ])
            ->add('cargoRel',EntityType::class,[
                'required' => false,
                'class' => 'App\Entity\RecursoHumano\RhuCargo',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Cargo:'
            ])
            ->add('identificacionRel',EntityType::class,[
                'required' => false,
                'class' => 'App\Entity\General\GenIdentificacion',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Identificacion:'
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
            ->add('ciudadNacimientoRel',EntityType::class,[
                'required' => false,
                'class' => 'App\Entity\General\GenCiudad',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cn')
                        ->orderBy('cn.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Ciudad nacimiento:'
            ])
            ->add('ciudadExpedicionRel',EntityType::class,[
                'required' => false,
                'class' => 'App\Entity\General\GenCiudad',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('ce')
                        ->orderBy('ce.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Ciudad expedicion:'
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
            ->add('fechaExpedicion', DateType::class, array('widget' => 'single_text', 'format' => 'yyyy-MM-dd','attr' => array('class' => 'date',)))
            ->add('numeroIdentificacion', TextType::class, array('required' => true))
            ->add('nombre1', TextType::class, array('required' => true))
            ->add('nombre2', TextType::class, array('required' => true))
            ->add('apellido1', TextType::class, array('required' => true))
            ->add('apellido2', TextType::class, array('required' => true))
            ->add('telefono', TextType::class, array('required' => true))
            ->add('celular', TextType::class, array('required' => true))
            ->add('direccion', TextType::class, array('required' => true))
            ->add('barrio', TextType::class, array('required' => true))
            ->add('correo', TextType::class, array('required' => true))
            ->add('fechaNacimiento', DateType::class, array('widget' => 'single_text', 'format' => 'yyyy-MM-dd','attr' => array('class' => 'date',)))
            ->add('comentarios', TextType::class, array('required' => true))
            ->add('guardar', SubmitType::class, ['label'=>'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RhuSeleccion::class,
        ]);
    }

}
