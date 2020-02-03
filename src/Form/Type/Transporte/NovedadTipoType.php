<?php

namespace App\Form\Type\Transporte;

use App\Entity\Transporte\TteGuiaTipo;
use App\Entity\Transporte\TteNovedadTipo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

class NovedadTipoType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('codigoNovedadTipoPk',TextType::class, array('required' => true,'label'=>"Codigo novedad tipo:"))
            ->add('nombre',TextType::class, array('required' => true,'label'=>'Nombre:'))
            ->add('interna', CheckboxType::class, array('required'  => false, 'label' => 'Interna'))
            ->add('btnGuardar', SubmitType::class,array('label'=>'Guardar','attr'=>['class'=>'btn btn-primary btn-sm']));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\Transporte\TteNovedadTipo'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'App_novedadTipo';
    }

}
