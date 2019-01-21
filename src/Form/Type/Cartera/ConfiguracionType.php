<?php

namespace App\Form\Type\Cartera;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

class ConfiguracionType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('codigoCuentaRetencionIvaFk', TextType::class, ['label' => 'codigoCuentaRetencionIvaFk:', 'required' => false])
            ->add('codigoCuentaRetencionFuenteFk', TextType::class, ['label' => 'codigoCuentaRetencionFuenteFk:', 'required' => false])
            ->add('codigoCuentaIndustriaComercioFk', TextType::class, ['label' => 'codigoCuentaIndustriaComercioFk:', 'required' => false])
            ->add('codigoCuentaAjustePesoFk', TextType::class, ['label' => 'codigoCuentaAjustePesoFk:', 'required' => false])
            ->add('guardar', SubmitType::class,array('label'=>'Guardar'));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\Cartera\CarConfiguracion'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'App_cartera';
    }



}
