<?php

namespace App\Form\Type\Cartera;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

class DescuentoConceptoType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('codigoDescuentoConceptoPk', TextType::class, array('label' => 'Codigo:', 'required' => true))
            ->add('nombre', TextType::class, array('label' => 'Nombre:','required' => true))
            ->add('codigoCuentaFk', TextType::class, array('label' => 'Cuenta:','required' => true))
            ->add('guardar', SubmitType::class,array('label'=>'Guardar', 'attr' => ['class' => 'btn btn-sm btn-primary']));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\Cartera\CarDescuentoConcepto'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'App_cartera';
    }

    public function getEstructuraPropiedadesLista()
    {
        $campos = '[
            {"campo":"codigoDescuentoConceptoPk", "tipo":"pk",    "ayuda":"Codigo del registro",                    "titulo":"ID"},
            {"campo":"nombre",             "tipo":"texto", "ayuda":"Nombre del registro",                    "titulo":"NOMBRE"},
            {"campo":"codigoCuentaFk",             "tipo":"texto", "ayuda":"Cuenta",                    "titulo":"CUENTA"}
        ]';
        return $campos;
    }

}
