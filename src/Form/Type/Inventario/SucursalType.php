<?php

namespace App\Form\Type\Inventario;

use App\Entity\General\GenCiudad;
use App\Entity\Inventario\InvSucursal;
use App\Entity\Inventario\InvTercero;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SucursalType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("codigoSucursalPk", TextType::class, ['required' => true, 'label' => 'nombre'])
            ->add("nombre", TextType::class, ['required' => true, 'label' => 'nombre'])
            ->add('btnGuardar', SubmitType::class, ['label' => 'Guardar']);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => InvSucursal::class,
        ]);
    }



}
