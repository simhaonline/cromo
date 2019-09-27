<?php

namespace App\Form\Type\Cartera;

use App\Entity\Cartera\CarAplicacion;
use App\Entity\Cartera\CarCuentaCobrar;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AplicacionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoAplicacionPk');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CarAplicacion::class,
        ]);
    }


}