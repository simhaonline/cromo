<?php

namespace App\Form\Type\General;

use App\Entity\General\GenConfiguracion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConfiguracionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nit', TextType::class, ['required' => true])
            ->add('digitoVerificacion', NumberType::class, ['required' => true])
            ->add('nombre', TextType::class, ['required' => true])
            ->add('rutaTemporal', TextType::class, ['required' => true])
            ->add('telefono', TextType::class, ['required' => true])
            ->add('direccion', TextType::class, ['required' => true])
            ->add('codigoClienteOro', IntegerType::class, ['required' => true])
            ->add('webServiceOroUrl', TextType::class, ['required' => false])
            ->add('webServiceCesioUrl', TextType::class, ['required' => false])
            ->add('dominio', TextType::class, ['required' => false])
            ->add('codigoLicenciaFk',TextType::class,['required'=>true])
            ->add('guardar', SubmitType::class, ['label' => 'Actualizar', 'attr' => ['class' => 'btn btn-sm btn-primary']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => GenConfiguracion::class,
        ]);
    }
}
