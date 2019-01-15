<?php

namespace App\Form\Type\Transporte;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class FacturaDetalleConceptoType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('facturaConceptoDetalleRel',EntityType::class,[
                'required' => false,
                'class' => 'App\Entity\Transporte\TteFacturaConceptoDetalle',
                'query_builder' => function (EntityRepository $er) use ($options) {
                    return $er->createQueryBuilder('fcd')
                        ->orderBy('fcd.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Fecha concepto:',
                'required' => true
            ])
            ->add('vrPrecio', IntegerType::class, array('required' => true))
            ->add('cantidad', IntegerType::class, array('required' => true))
            ->add('guardar', SubmitType::class,array('label'=>'Guardar'));
    }


    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\Transporte\TteFacturaDetalleConcepto'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'App_facturaDetalleConcepto';
    }

}
