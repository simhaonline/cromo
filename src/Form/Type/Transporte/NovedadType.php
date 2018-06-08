<?php

namespace App\Form\Type\Transporte;

use App\Entity\Transporte\TteGuiaTipo;
use App\Entity\Transporte\TteNovedadTipo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

class NovedadType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('novedadTipoRel', EntityType::class, array(
                'class' => TteNovedadTipo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('nt')
                        ->orderBy('nt.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
            ))
            ->add('fecha', DateTimeType::class)
            ->add('fechaReporte', DateTimeType::class)
            ->add('descripcion',TextareaType::class, array('required' => false))
            ->add('estadoAtendido', CheckboxType::class, array('required' => false))
            ->add('guardar', SubmitType::class,array('label'=>'Guardar'));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\Transporte\TteNovedad'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'App_novedad';
    }

}
