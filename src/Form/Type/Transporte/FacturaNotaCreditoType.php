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

class FacturaNotaCreditoType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('facturaTipoRel',EntityType::class,[
                'required' => false,
                'class' => 'App\Entity\Transporte\TteFacturaTipo',
                'query_builder' => function (EntityRepository $er) use ($options) {
                    return $er->createQueryBuilder('ft')
                        ->orderBy('ft.nombre', 'ASC')
                        ->where('ft.guiaFactura = 0')
                        ->andWhere("ft.codigoFacturaClaseFk = '". $options['data']->getCodigoFacturaClaseFk() ."'");
                },
                'choice_label' => 'nombre',
                'label' => 'Fecha tipo:',
                'required' => true
            ])
            ->add('facturaConceptoRel',EntityType::class,[
                'required' => false,
                'class' => 'App\Entity\Transporte\TteFacturaConcepto',
                'query_builder' => function (EntityRepository $er) use ($options) {
                    return $er->createQueryBuilder('ft')
                        ->orderBy('ft.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Concepto:',
                'required' => true
            ])
            ->add('soporte', TextType::class,['required' => false])
            ->add('plazoPago', NumberType::class)
            ->add('comentario',TextareaType::class ,['required' => false,'label' => 'Comentarios:'])
            ->add('guardar', SubmitType::class,array('label'=>'Guardar'))
            ->add('guardarnuevo', SubmitType::class,array('label'=>'Guardar y nuevo'));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\Transporte\TteFactura'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'App_factura';
    }

}
