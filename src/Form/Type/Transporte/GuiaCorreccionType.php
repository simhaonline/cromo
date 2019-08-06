<?php

namespace App\Form\Type\Transporte;

use App\Entity\Transporte\TteCiudad;
use App\Entity\Transporte\TteOperacion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

class GuiaCorreccionType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('codigoClienteFk', TextType::class)
            ->add('documentoCliente', TextType::class, array('required' => false))
            ->add('fechaIngreso', DateType::class,array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('ciudadOrigenRel',EntityType::class,[
                'class' => TteCiudad::class,
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('er')
                        ->orderBy('er.nombre');
                },'choice_label' => 'nombre',
                'required' => true
            ])
            ->add('ciudadDestinoRel',EntityType::class,[
                'class' => TteCiudad::class,
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('er')
                        ->orderBy('er.nombre');
                },'choice_label' => 'nombre',
                'required' => true
            ])
            ->add('operacionIngresoRel',EntityType::class,[
                'class' => TteOperacion::class,
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('o')
                        ->orderBy('o.nombre');
                },'choice_label' => 'nombre',
                'required' => true
            ])
            ->add('operacionCargoRel',EntityType::class,[
                'class' => TteOperacion::class,
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('o')
                        ->orderBy('o.nombre');
                },'choice_label' => 'nombre',
                'required' => true
            ])
            ->add('estadoEntregado', CheckboxType::class, array('required'  => false))
            ->add('estadoSoporte', CheckboxType::class, array('required'  => false))
            ->add('guardar',SubmitType::class,['label' => 'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\Transporte\TteGuia'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'App_guia';
    }

}
