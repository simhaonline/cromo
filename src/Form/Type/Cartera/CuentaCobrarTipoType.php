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

class CuentaCobrarTipoType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('codigoCuentaCobrarTipoPk', TextType::class, ['label'=> 'Codigo cuenta cobrar tipo pk:', 'required' => true])
            ->add('nombre', TextType::class, ['label' => 'Nombre:', 'required' => true])
            ->add('codigoCuentaClienteFk', TextType::class, ['label' => 'Codigo cuenta cliente:', 'required' => false])
            ->add('codigoCuentaAplicacionFk', TextType::class, ['label' => 'Codigo cuenta aplicacion:', 'required' => false])
            ->add('operacion', IntegerType::class, ['label' => 'Operacion:', 'required' => false])
            ->add('saldoInicial', CheckboxType::class, ['required' => false])
            ->add('prefijo', TextType::class, ['label' => 'Prefijo:', 'required' => false])
            ->add('guardar', SubmitType::class,array('label'=>'Guardar'));
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

    public function getEstructuraPropiedadesLista()
    {
        $campos = '[
            {"campo":"codigoCuentaCobrarTipoPk",         "tipo":"pk"     ,"ayuda":"Codigo del registro",           "titulo":"ID"},
            {"campo":"prefijo",            "tipo":"texto"  ,"ayuda":"prefijo",     "titulo":"PREFIJO"},
            {"campo":"nombre",                           "tipo":"texto"  ,"ayuda":"Nombre del tipo de anticipo",   "titulo":"NOMBRE"},
            {"campo":"codigoCuentaClienteFk",            "tipo":"texto"  ,"ayuda":"cuenta cliente",     "titulo":"CUENTA CLIENTE"},
            {"campo":"codigoCuentaAplicacionFk",            "tipo":"texto"  ,"ayuda":"cuenta aplicacion",     "titulo":"CUENTA APLICACION"},
            {"campo":"saldoInicial",            "tipo":"bool"  ,"ayuda":"Saldo inicial",     "titulo":"SI"}      
                                                                          
        ]';
        return $campos;
    }

    public function getEstructuraPropiedadesExportar()
    {
        $campos = '[
            {"campo":"codigoCuentaCobrarTipoPk",         "tipo":"pk"     ,"ayuda":"Codigo del registro",           "titulo":"ID"},
            {"campo":"nombre",                           "tipo":"texto"  ,"ayuda":"Nombre del tipo de anticipo",   "titulo":"NOMBRE"},
            {"campo":"codigoCuentaClienteFk",            "tipo":"texto"  ,"ayuda":"cuenta cliente",     "titulo":"CUENTA CLIENTE"},
            {"campo":"codigoCuentaRetencionFuenteFk",            "tipo":"texto"  ,"ayuda":"cuenta retencion fuente",     "titulo":"CUENTA RETENCION FUENTE"},
            {"campo":"codigoCuentaRetencionIvaFk",            "tipo":"texto"  ,"ayuda":"cuenta retencion fuente",     "titulo":"CUENTA RETENCION IVA"},
            {"campo":"codigoCuentaAjustePesoFk",            "tipo":"texto"  ,"ayuda":"cuenta retencion fuente",     "titulo":"CUENTA RETENCION ICA"}                                          
        ]';
        return $campos;
    }

}
