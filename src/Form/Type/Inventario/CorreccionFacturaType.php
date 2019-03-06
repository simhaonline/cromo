<?php

namespace App\Form\Type\Inventario;

use App\Entity\General\GenAsesor;
use App\Entity\Inventario\InvMovimiento;
use App\Entity\Transporte\TteCiudad;
use App\Entity\Transporte\TteOperacion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

class CorreccionFacturaType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('asesorRel',EntityType::class,[
                'class' => GenAsesor::class,
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('a')
                        ->orderBy('a.nombre');
                },'choice_label' => 'nombre',
                'required' => true
            ])
            ->add('guardar',SubmitType::class,['label' => 'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\Inventario\InvMovimiento'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'App_movimiento';
    }

}
