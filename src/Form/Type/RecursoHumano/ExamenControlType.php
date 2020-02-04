<?php

namespace App\Form\Type\RecursoHumano;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExamenControlType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
//                ->add('centroCostoRel', EntityType::class, array(
//                'class' => 'App\Entity\RecursoHumano\RhuCentroCosto',
//                'query_builder' => function (EntityRepository $er) {
//                    return $er->createQueryBuilder('cc')
//                    ->orderBy('cc.nombre', 'ASC');},
//                'choice_label' => 'nombre',
//                'required' => true))
                ->add('examenClaseRel', EntityType::class, array(
                    'class' => 'App\Entity\RecursoHumano\RhuExamenClase',
                    'choice_label' => 'nombre',
                ))
                ->add('cargoRel', EntityType::class, array(
                    'class' => 'App\Entity\RecursoHumano\RhuCargo',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('c')
                                ->orderBy('c.nombre', 'ASC');
                    },
                    'choice_label' => 'nombre',
                ))
                ->add('entidadExamenRel', EntityType::class, array(
                    'class' => 'App\Entity\RecursoHumano\RhuEntidadExamen',
                    'choice_label' => 'nombre',
                ))
                ->add('ciudadRel', EntityType::class, array(
                    'class' => 'App\Entity\General\GenCiudad',
                    'choice_label' => 'nombre',
                ))
                ->add('fecha', DateTimeType::class)
                ->add('codigoSexoFk', ChoiceType::class, array('choices' => array('MASCULINO' => 'M', 'FEMENINO' => 'F')))
                ->add('comentarios', TextareaType::class, array('required' => false))
                ->add('identificacion', TextType::class, array('required' => true))
                ->add('nombreCorto', TextType::class, array('required' => true))
                ->add('fechaNacimiento', BirthdayType::class)
                ->add('controlPago', ChoiceType::class, array('choices' => array('SI' => '1', 'NO' => '0')))
                ->add('controlPago', CheckboxType::class, array('required' => false))
                ->add('cobro', ChoiceType::class, array('choices' => array('CLIENTE' => 'C', 'EMPLEADO' => 'E', 'NO COBRAR' => 'N')))                                            
                ->add('guardar', SubmitType::class);
    }
    
    public function getBlockPrefix() {
        return 'form';
    }

}
