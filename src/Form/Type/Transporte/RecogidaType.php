<?php

namespace App\Form\Type\Transporte;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class   RecogidaType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('conductorRel',EntityType::class,[
                'required' => false,
                'class' => 'App\Entity\Transporte\TteConductor',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.nombreCorto', 'ASC');
                },
                'choice_label' => 'nombreCorto',
                'label' => 'Conductor:'
            ])
            ->add('vehiculoRel',EntityType::class,[
                'required' => false,
                'class' => 'App\Entity\Transporte\TteVehiculo',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('v')
                        ->orderBy('v.placa', 'ASC');
                },
                'choice_label' => 'placa',
                'label' => 'Vehiculo:'
            ])
            ->add('ciudadRel',EntityType::class,[
                'required' => true,
                'class' => 'App\Entity\Transporte\TteCiudad',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Ciudad:'
            ])
            ->add('rutaRecogidaRel',EntityType::class,[
                'required' => false,
                'class' => 'App\Entity\Transporte\TteRutaRecogida',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('rr')
                        ->orderBy('rr.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Ruta:'
            ])
            ->add("direccion",TextType::class,['required'=>true])
            ->add("telefono",TextType::class,['required'=>true])
            ->add('fecha', DateTimeType::class)
            ->add('unidades', NumberType::class)
            ->add('pesoReal', NumberType::class)
            ->add('pesoVolumen', NumberType::class)
            ->add('comentario', TextareaType::class,['required'=>false])
            ->add('guardar', SubmitType::class,array('label'=>'Guardar'))
            ->add('guardarnuevo', SubmitType::class, ['label'=>'Guardar y nuevo','attr' => ['class' => 'btn btn-sm btn-primary']]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\Transporte\TteRecogida'
        ));
    }

    public function getOrdenamiento(){
        $campos ='[
            {"campo":"fecha","tipo":"DESC"}
        ]';
        return $campos;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'App_recogida';
    }

}
