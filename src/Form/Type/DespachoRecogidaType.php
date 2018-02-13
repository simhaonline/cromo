<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class DespachoRecogidaType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('comentario',TextareaType::class, array('required' => true))
            ->add('guardar', SubmitType::class,array('label'=>'Guardar'))
                ->add('guardarnuevo', SubmitType::class,array('label'=>'Guardar y nuevo'));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\DespachoRecogida'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'App_recogida';
    }

}
