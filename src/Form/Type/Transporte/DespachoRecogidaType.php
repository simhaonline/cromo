<?php

namespace App\Form\Type\Transporte;

use App\Entity\Seguridad\Usuario;
use App\Entity\Transporte\TteDespachoRecogidaTipo;
use App\Entity\Transporte\TteRutaRecogida;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

class DespachoRecogidaType extends AbstractType {
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('despachoRecogidaTipoRel', EntityType::class, array(
                'class' => TteDespachoRecogidaTipo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('drt')
                        ->orderBy('drt.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
            ))
            ->add('rutaRecogidaRel', EntityType::class, array(
                'class' => TteRutaRecogida::class,
                'query_builder' => function (EntityRepository $er) use ($options) {
                    return $er->createQueryBuilder('rr')
                        ->orderBy('rr.nombre', 'ASC')
                        ->where("rr.codigoOperacionFk = '". $options['data']->getOperacionRel()->getCodigoOperacionPk() ."'");
                },
                'choice_label' => 'nombre',
                'required' => true,
                'placeholder' => ''
            ))
            ->add('vrFletePago', NumberType::class)
            ->add('vrCostoPago', NumberType::class)
            ->add('vrAnticipo', NumberType::class)
            ->add('vrDescuentoPapeleria', NumberType::class)
            ->add('vrDescuentoSeguridad', NumberType::class)
            ->add('vrDescuentoCargue', NumberType::class)
            ->add('vrDescuentoEstampilla', NumberType::class)
            ->add('comentario',TextareaType::class, array('required' => false))
            ->add('guardar', SubmitType::class,array('label'=>'Guardar'));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\Transporte\TteDespachoRecogida'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'App_despacho_recogida';
    }

}
