<?php

namespace App\Form\Type\Transporte;

use App\Entity\Transporte\TteDespachoTipo;
use App\Entity\Transporte\TteRuta;
use App\Entity\Transporte\TteCiudad;
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

class DespachoType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('despachoTipoRel', EntityType::class, array(
                'class' => TteDespachoTipo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('dt')
                        ->orderBy('dt.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
            ))
            ->add('ciudadOrigenRel', EntityType::class, array(
                'class' => TteCiudad::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
            ))
            ->add('ciudadDestinoRel', EntityType::class, array(
                'class' => TteCiudad::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
            ))
            ->add('rutaRel', EntityType::class, array(
                'class' => TteRuta::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('rr')
                        ->orderBy('rr.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
            ))
            ->add('vrFletePago', NumberType::class)
            ->add('vrAnticipo', NumberType::class)
            ->add('vrDescuentoPapeleria', NumberType::class)
            ->add('vrDescuentoSeguridad', NumberType::class)
            ->add('vrDescuentoCargue', NumberType::class)
            ->add('vrDescuentoEstampilla', NumberType::class)
            ->add('comentario',TextareaType::class, array('required' => false))
            ->add('guardar', SubmitType::class,array('label'=>'Guardar'))
                ->add('guardarnuevo', SubmitType::class,array('label'=>'Guardar y nuevo'));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\Transporte\TteDespacho'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'App_despacho';
    }

}
