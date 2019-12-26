<?php

namespace App\Form\Type\General;

use App\Entity\General\GenResolucion;
use App\Entity\General\GenSexo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ResolucionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('numero',TextType::class,['required' => true,'label' => 'Numero:'])
            ->add('prefijo',TextType::class,['required' => true,'label' => 'Prefijo:'])
            ->add('numeroDesde',TextType::class,['required' => true,'label' => 'Numero desde:'])
            ->add('numeroHasta',TextType::class,['required' => true,'label' => 'Numero hasta:'])
            ->add('llaveTecnica',TextType::class,['required' => true,'label' => 'Llave tecnica:'])
            ->add('setPruebas',TextType::class,['required' => true,'label' => 'Set pruebas:'])
            ->add('pin',TextType::class,['required' => true,'label' => 'Pin:'])
            ->add('ambiente',TextType::class,['required' => true,'label' => 'Ambiente:'])
            ->add('prueba', CheckboxType::class, ['required' => false])
            ->add('fecha', DateType::class, ['widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)])
            ->add('fechaDesde', DateType::class, ['widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)])
            ->add('fechaHasta', DateType::class, ['widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)])
            ->add('guardar', SubmitType::class, ['label'=>'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => GenResolucion::class,
        ]);
    }

}
