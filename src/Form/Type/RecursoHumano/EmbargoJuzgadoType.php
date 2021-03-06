<?php

namespace App\Form\Type\RecursoHumano;

use App\Entity\RecursoHumano\RhuEmbargoJuzgado;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmbargoJuzgadoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoEmbargoJuzgadoPk',TextType::class,['required' => true,'label' => 'Codigo juzgado:'])
            ->add('nombre',TextType::class,['required' => true,'label' => 'Nombre:'])
            ->add('oficina',TextType::class,['required' => true,'label' => 'Oficina:'])
            ->add('cuenta',TextType::class,['required' => false, 'label' =>'Cuenta:'])
            ->add('guardar',SubmitType::class,['label' => 'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RhuEmbargoJuzgado::class,
        ]);
    }

}
