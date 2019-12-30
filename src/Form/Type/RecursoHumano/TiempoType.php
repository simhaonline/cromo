<?php

namespace App\Form\Type\RecursoHumano;

use App\Entity\RecursoHumano\RhuTiempo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TiempoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoTiempoPk',TextType::class,['required' => true,'label' => 'Codigo tiempo:'])
            ->add('nombre',TextType::class,['required' => true,'label' => 'Nombre:'])
            ->add('factor',IntegerType::class,['required' => true,'label' => 'Factor:'])
            ->add('factorHorasDia',IntegerType::class,['required' => true,'label' => 'Factor horas dia:'])
            ->add('orden',IntegerType::class,['required' => true,'label' => 'Orden:'])
            ->add('abreviatura',TextType::class,['required' => true,'label' => 'Abreviatura:'])
            ->add('guardar',SubmitType::class,['attr' => ['class' => 'btn btn-sm btn-primary']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RhuTiempo::class,
        ]);
    }

}
