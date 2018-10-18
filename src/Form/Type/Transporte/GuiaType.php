<?php

namespace App\Form\Type\Transporte;

use App\Entity\Transporte\TteCiudad;
use App\Entity\Transporte\TteEmpaque;
use App\Entity\Transporte\TteGuiaTipo;
use App\Entity\Transporte\TteRuta;
use App\Entity\Transporte\TteServicio;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

class GuiaType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('servicioRel', EntityType::class, array(
                'class' => TteServicio::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('s')
                        ->orderBy('s.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
            ))
            ->add('empaqueRel', EntityType::class, array(
                'class' => TteEmpaque::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('e')
                        ->orderBy('e.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
            ))
            ->add('ciudadDestinoRel', EntityType::class, array(
                'class' => TteCiudad::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
            ))
            ->add('documentoCliente', TextType::class)
            ->add('remitente', TextType::class)
            ->add('nombreDestinatario', TextType::class)
            ->add('direccionDestinatario', TextType::class)
            ->add('telefonoDestinatario', TextType::class)
            ->add('unidades', NumberType::class)
            ->add('pesoReal', NumberType::class)
            ->add('pesoVolumen', NumberType::class)
            ->add('vrDeclara', NumberType::class)
            ->add('vrFlete', NumberType::class)
            ->add('vrManejo', NumberType::class)
            ->add('comentario',TextareaType::class, array('required' => false))
            ->add('guardar',SubmitType::class,['label' => 'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\Transporte\TteGuia'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'App_despacho';
    }

}
