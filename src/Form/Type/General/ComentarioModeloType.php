<?php

namespace App\Form\Type\General;


use App\Entity\General\GenComentarioModelo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ComentarioModeloType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('comentario', TextareaType::class, ['attr' => ['rows' => '1','placeholder' => 'Escribe un comentario...','style' => '-webkit-border-radius: 50px!important;-moz-border-radius: 50px!important;border-radius: 50px!important;']])
            ->add('comentar',SubmitType::class,['attr' => ['class' => 'btn btn-sm btn-primary','style' => 'border-radius:10px;']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => GenComentarioModelo::class,
        ]);
    }
}
