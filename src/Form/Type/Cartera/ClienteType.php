<?php

namespace App\Form\Type\Cartera;

use App\Entity\General\GenCiudad;
use App\Entity\General\GenFormaPago;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

class ClienteType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('formaPagoRel', EntityType::class, array(
                'class' => GenFormaPago::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('fp')
                        ->orderBy('fp.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
            ))
            ->add('ciudadRel', EntityType::class, array(
                'class' => GenCiudad::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
            ))
            ->add('numeroIdentificacion', IntegerType::class, array('required' => true))
            ->add('digitoVerificacion',IntegerType::class, array('required' => false))
            ->add('nombreCorto',TextType::class, array('required' => true))
            ->add('direccion',TextType::class, array('required' => false))
            ->add('telefono',IntegerType::class, array('required' => false))
            ->add('celular',IntegerType::class, array('required' => false))
            ->add('fax', IntegerType::class, array('required' => false))
            ->add('correo', TextType::class, array('required' => false))
            ->add('contacto', TextType::class, array('required' => false))
            ->add('plazoPago', IntegerType::class, array('required' => true))
            ->add('contactoTelefono', IntegerType::class, array('required' => false))
            ->add('guardar', SubmitType::class,array('label'=>'Guardar'))
            ->add('guardarnuevo', SubmitType::class,array('label'=>'Guardar y nuevo'));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\Cartera\CarCliente'
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
            {"campo":"codigoClientePk", "tipo":"pk",    "ayuda":"Codigo del registro",                    "titulo":"ID"},
            {"campo":"nombreCorto",             "tipo":"texto", "ayuda":"Nombre del registro",                    "titulo":"NOMBRE"}
        ]';
        return $campos;
    }

}
