<?php

namespace App\Form\Type\Cartera;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

class CuentaCobrarTipoType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('nombre', TextType::class, array('required' => true))
            ->add('tipoCuentaCliente', ChoiceType::class,array('choices' => array('DEBITO' => '1', 'CREDITO' => '2'),'required' => false))
            ->add('codigoCuentaClienteFk', TextType::class, array('required' => false))
            ->add('codigoCuentaRetencionFuenteFk', TextType::class, array('required' => false))
            ->add('codigoCuentaRetencionIvaFk', TextType::class, array('required' => false))
            ->add('codigoCuentaRetencionIcaFk', TextType::class, array('required' => false))
            ->add('guardar', SubmitType::class,array('label'=>'Guardar'))
            ->add('guardarnuevo', SubmitType::class,array('label'=>'Guardar y nuevo'));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\Cartera\CarCuentaCobrarTipo'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'App_cartera';
    }

}
