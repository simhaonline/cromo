<?php

namespace App\Form\Type\RecursoHumano;

use App\Entity\RecursoHumano\RhuCostoClase;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CostoClaseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoCostoClasePk',TextType::class,['required' => true, 'label' => 'Codigo costo clase:'])
            ->add('nombre',TextType::class,['required' => true, 'label' => 'Nombre:'])
            ->add('operativo',CheckboxType::class,['required' => false,'label' => 'Operativo'])
            ->add('orden',IntegerType::class,['required' => true,'label' => 'Orden'])
            ->add('guardar', SubmitType::class, ['attr' => ['class' => 'btn btn-sm btn-primary']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RhuCostoClase::class,
        ]);
    }
}
