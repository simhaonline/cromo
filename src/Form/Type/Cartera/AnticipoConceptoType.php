<?php

namespace App\Form\Type\Cartera;

use App\Entity\Cartera\CarAnticipoConcepto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnticipoConceptoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("codigoAnticipoConceptoPk", TextType::class, ['required' => true, 'label' => 'nombre'])
            ->add("nombre", TextType::class, ['required' => true, 'label' => 'nombre'])
            ->add('btnGuardar', SubmitType::class, ['label' => 'Guardar']);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CarAnticipoConcepto::class,
        ]);
    }

}
