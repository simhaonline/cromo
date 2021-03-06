<?php

namespace App\Form\Type\Transporte;

use App\Entity\Transporte\TteDespachoTipo;
use App\Entity\Transporte\TteOperacion;
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

class DespachoType extends AbstractType
{

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('operacionRel', EntityType::class, array(
                'class' => TteOperacion::class,
                'required' => true,
                'query_builder' => function (EntityRepository $er) use ($options) {
                    return $er->createQueryBuilder('o')
                        ->orderBy('o.nombre', 'ASC');
                },
                'choice_label' => 'nombre'
            ))
            ->add('despachoTipoRel', EntityType::class, array(
                'class' => TteDespachoTipo::class,
                'required' => true,
                'query_builder' => function (EntityRepository $er) use ($options) {
                    return $er->createQueryBuilder('dt')
                        ->orderBy('dt.nombre', 'ASC')
                        ->andWhere("dt.codigoDespachoClaseFk = '" . $options['data']->getCodigoDespachoClaseFk() . "'");
                },
                'choice_label' => 'nombre',
                'placeholder' => ''
            ))
            ->add('ciudadOrigenRel', EntityType::class, array(
                'class' => TteCiudad::class,
                'required' => true,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'placeholder' => ''
            ))
            ->add('ciudadDestinoRel', EntityType::class, array(
                'class' => TteCiudad::class,
                'required' => true,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'placeholder' => ''
            ))
            ->add('rutaRel', EntityType::class, array(
                'class' => TteRuta::class,
                'required' => true,
                'query_builder' => function (EntityRepository $er) use ($options) {
                    return $er->createQueryBuilder('rr')
                        ->orderBy('rr.nombre', 'ASC')
                        ->where("rr.codigoOperacionFk = '" . $options['data']->getOperacionRel()->getCodigoOperacionPk() . "'")
                        ->andWhere("rr.codigoDespachoClaseFk = '" . $options['data']->getCodigoDespachoClaseFk() . "'");
                },
                'choice_label' => 'nombre',
                'placeholder' => ''
            ))
            ->add('vrFletePago', NumberType::class)
            ->add('vrCostoPago', NumberType::class)
            ->add('vrAnticipo', NumberType::class)
            ->add('vrDescuentoPapeleria', NumberType::class)
            ->add('vrDescuentoSeguridad', NumberType::class)
            ->add('vrDescuentoCargue', NumberType::class)
            ->add('vrDescuentoEstampilla', NumberType::class)
            ->add('comentario', TextareaType::class, array('required' => false))
            ->add('guardar', SubmitType::class, array('label' => 'Guardar'));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\Transporte\TteDespacho'
        ));
    }


    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'App_despacho';
    }

}
