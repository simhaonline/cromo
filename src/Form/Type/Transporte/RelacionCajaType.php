<?php

namespace App\Form\Type\Transporte;

use App\Entity\Transporte\TteRelacionCaja;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

class RelacionCajaType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('fecha', DateType::class)
            ->add('comentario',TextareaType::class, array('required' => false))
            ->add('guardar', SubmitType::class,array('label'=>'Guardar'))
                ->add('guardarnuevo', SubmitType::class,array('label'=>'Guardar y nuevo'));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\Transporte\TteRelacionCaja'
        ));
    }


    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'App_despacho';
    }

}
