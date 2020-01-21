<?php

namespace App\Form\Type\RecursoHumano;


use App\Entity\RecursoHumano\RhuEstudioTipo;
use App\Entity\RecursoHumano\RhuSolicitud;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SolicitudType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('grupoRel',EntityType::class,[
                'required' => true,
                'class' => 'App\Entity\RecursoHumano\RhuGrupo',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('gp')
                        ->orderBy('gp.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Grupo pago:'
            ])
            ->add('solicitudMotivoRel',EntityType::class,[
                'required' => true,
                'class' => 'App\Entity\RecursoHumano\RhuSolicitudMotivo',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('sm')
                        ->orderBy('sm.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Solicitud motivo:'
            ])
            ->add('cargoRel',EntityType::class,[
                'required' => true,
                'class' => 'App\Entity\RecursoHumano\RhuCargo',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Cargo:'
            ])
            ->add('clasificacionRiesgoRel',EntityType::class,[
                'required' => true,
                'class' => 'App\Entity\RecursoHumano\RhuClasificacionRiesgo',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cr')
                        ->orderBy('cr.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Clasificacion riesgo:'
            ])
            ->add('ciudadRel',EntityType::class,[
                'required' => true,
                'class' => 'App\Entity\General\GenCiudad',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cs')
                        ->orderBy('cs.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Ciudad residencia:'
            ])
            ->add('estudioTipoRel',EntityType::class,[
                'required' => true,
                'class' => RhuEstudioTipo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('et')
                        ->orderBy('et.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Nivel de estudio:'
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
            ->add('solicitudExperienciaRel',EntityType::class,[
                'required' => false,
                'class' => 'App\Entity\RecursoHumano\RhuSolicitudExperiencia',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('se')
                        ->orderBy('se.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Experiencia:'
            ])
            ->add('nombre',TextType::class,['required' => true,'label' => 'Nombre:'])
            ->add('cantidadSolicitada',IntegerType::class,['required' => true,'label' => 'Cantidad:'])
            ->add('vrSalario', NumberType::class,['required' => true,'label' => 'Salario:'])
            ->add('vrNoSalarial',NumberType::class,['required' => false,'label' => 'No salarial:'])
            ->add('fechaContratacion', DateType::class,array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('fechaVencimiento', DateType::class,array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('edadMinima', TextType::class, ['required' => false,'label' => 'Edad minima:'])
            ->add('edadMaxima', TextType::class, ['required' => false,'label' => 'Edad maxima:'])
//            ->add('salarioFijo', RadioType::class, array('required'  => false))
//            ->add('salarioVariable', RadioType::class, array('required'  => false))
            ->add('numeroHijos', NumberType::class, ['required' => false,'label' => 'Numero de hijos:'])
            ->add('codigoTipoVehiculoFk', ChoiceType::class, array('choices'   => array('CARRO' => '1', 'MOTO' => '2', 'NO APLICA' => '0')))
            ->add('codigoLicenciaCarroFk', ChoiceType::class, array('choices'   => array('SI' => '1', 'NO' => '2', 'NO APLICA' => '0')))
            ->add('codigoLicenciaMotoFk', ChoiceType::class, array('choices'   => array('SI' => '1', 'NO' => '2', 'NO APLICA' => '0')))
            ->add('disponbilidad',TextType::class,['required' => false,'label' => 'Disponibilidad:'])
            ->add('comentarios', TextareaType::class, array('required' => false))
            ->add('guardar', SubmitType::class, ['label'=>'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RhuSolicitud::class,
        ]);
    }

}
