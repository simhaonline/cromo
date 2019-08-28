<?php

namespace App\Form\Type\Tesoreria;

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

class CuentaPagarTipoType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('codigoCuentaPagarTipoPk', TextType::class, ['label'=> 'Codigo cuenta pagar tipo pk:', 'required' => true])
            ->add('nombre', TextType::class, ['label' => 'Nombre:', 'required' => true])
            ->add('operacion', IntegerType::class, ['label' => 'Operacion:', 'required' => false])
            ->add('codigoCuentaProveedorFk', TextType::class, ['label' => 'Cuenta proveedor:', 'required' => false])
            ->add('saldoInicial', CheckboxType::class, ['required' => false])
            ->add('prefijo', TextType::class, ['label' => 'Prefijo:', 'required' => false])
            ->add('guardar', SubmitType::class,array('label'=>'Guardar'));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\Tesoreria\TesCuentaPagarTipo'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'App_tesoreria';
    }

    public function getEstructuraPropiedadesLista()
    {
        $campos = '[
            {"campo":"codigoCuentaPagarTipoPk",         "tipo":"pk"     ,"ayuda":"Codigo del registro",           "titulo":"ID"},
            {"campo":"prefijo",            "tipo":"texto"  ,"ayuda":"prefijo",     "titulo":"PREFIJO"},
            {"campo":"nombre",                           "tipo":"texto"  ,"ayuda":"Nombre del tipo de anticipo",   "titulo":"NOMBRE"},
            {"campo":"codigoCuentaProveedorFk",                           "tipo":"texto"  ,"ayuda":"Cuenta proveedor",   "titulo":"CTA PRO"},
            {"campo":"saldoInicial",            "tipo":"bool"  ,"ayuda":"Saldo inicial",     "titulo":"SI"}      
                                                                          
        ]';
        return $campos;
    }

    public function getEstructuraPropiedadesExportar()
    {
        $campos = '[
            {"campo":"codigoCuentaPagarTipoPk",         "tipo":"pk"     ,"ayuda":"Codigo del registro",           "titulo":"ID"},
            {"campo":"nombre",                           "tipo":"texto"  ,"ayuda":"Nombre del tipo de anticipo",   "titulo":"NOMBRE"}                                       
        ]';
        return $campos;
    }

}
