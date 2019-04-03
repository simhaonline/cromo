<?php

namespace App\Form\Type\Transporte;

use App\Entity\Transporte\TteVehiculo;
use App\Entity\Transporte\TteVehiculoDisponible;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class VehiculoDisponibleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('responsable',TextType::class,['required' => true,'label' => 'Responsable:'])
            ->add('celular',TextType::class,['required' => true,'label' => 'Celular:'])
            ->add('comentario',TextareaType::class,['required' => false,'label' => 'Comentarios:'])
            ->add('guardar', SubmitType::class, ['label'=>'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TteVehiculoDisponible::class,
        ]);
    }

}
