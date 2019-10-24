<?php

namespace App\Form\Type\Transporte;

use App\Entity\Transporte\TteCiudad;
use App\Entity\Transporte\TteGuia;
use App\Entity\Transporte\TteGuiaTipo;
use App\Entity\Transporte\TteOperacion;
use App\Entity\Transporte\TteProducto;
use App\Entity\Transporte\TteRuta;
use App\Entity\Transporte\TteServicio;
use App\Entity\Transporte\TteZona;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

class GuiaCorreccionType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('codigoClienteFk', TextType::class)
            ->add('documentoCliente', TextType::class, array('required' => false))
            ->add('fechaIngreso', DateType::class,array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('ciudadOrigenRel',EntityType::class,[
                'class' => TteCiudad::class,
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('er')
                        ->orderBy('er.nombre');
                },'choice_label' => 'nombre',
                'required' => true
            ])
            ->add('ciudadDestinoRel',EntityType::class,[
                'class' => TteCiudad::class,
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('er')
                        ->orderBy('er.nombre');
                },'choice_label' => 'nombre',
                'required' => true
            ])
            ->add('operacionIngresoRel',EntityType::class,[
                'class' => TteOperacion::class,
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('o')
                        ->orderBy('o.nombre');
                },'choice_label' => 'nombre',
                'required' => true
            ])
            ->add('operacionCargoRel',EntityType::class,[
                'class' => TteOperacion::class,
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('o')
                        ->orderBy('o.nombre');
                },'choice_label' => 'nombre',
                'required' => true
            ])
            ->add('rutaRel',EntityType::class,[
                'class' => TteRuta::class,
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('r')
                        ->orderBy('r.nombre');
                },'choice_label' => 'nombre',
                'required' => true
            ])
            ->add('zonaRel',EntityType::class,[
                'class' => TteZona::class,
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('z')
                        ->orderBy('z.nombre');
                },'choice_label' => 'nombre',
                'required' => true
            ])
            ->add('productoRel',EntityType::class,[
                'class' => TteProducto::class,
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('p')
                        ->orderBy('p.nombre');
                },'choice_label' => 'nombre',
                'required' => true
            ])
            ->add('servicioRel',EntityType::class,[
                'class' => TteServicio::class,
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('s')
                        ->orderBy('s.nombre');
                },'choice_label' => 'nombre',
                'required' => true
            ])
            ->add('guiaTipoRel',EntityType::class,[
                'class' => TteGuiaTipo::class,
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('gt')
                        ->orderBy('gt.nombre');
                },'choice_label' => 'nombre',
                'required' => true
            ])
            ->add('tipoLiquidacion', TextType::class)
            ->add('estadoEntregado', CheckboxType::class, array('required'  => false))
            ->add('estadoSoporte', CheckboxType::class, array('required'  => false))
            ->add('guardar',SubmitType::class,['label' => 'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => TteGuia::class
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'App_guia';
    }

}
