<?php

namespace App\Form\Type\Inventario;

use App\Entity\Inventario\InvLinea;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LineaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoLineaPk',TextType::class, ['required' => true, 'label' => 'nombre'])
            ->add('nombre',TextType::class, ['required' => true, 'label' => 'nombre'])
            ->add('btnGuardar', SubmitType::class, ['label' => 'Guardar']);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => InvLinea::class,
        ]);
    }

}
