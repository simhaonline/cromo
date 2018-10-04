<?php

namespace App\Form\Type\Transporte;

use App\Entity\Transporte\TteConfiguracion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConfiguracionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('usuarioRndc',TextType::class,['required' => true])
            ->add('claveRndc',TextType::class,['required' => true])
            ->add('empresaRndc',TextType::class,['required' => true])
            ->add('numeroPoliza',NumberType::class,['required' => true])
            ->add('fechaVencePoliza', DateType::class,array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('numeroIdentificacionAseguradora',NumberType::class,['required' => true])
            ->add('codigoPrecioGeneralFk',IntegerType::class,['required' => true])
            ->add('vrBaseRetencionFuente',NumberType::class,['required' => true])
            ->add('porcentajeRetencionFuente',NumberType::class,['required' => true])
            ->add('porcentajeIndustriaComercio',NumberType::class,['required' => true])
            ->add('guardar',SubmitType::class,['label' => 'Actualizar','attr' => ['class' => 'btn btn-sm btn-primary']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TteConfiguracion::class,
        ]);
    }
}
