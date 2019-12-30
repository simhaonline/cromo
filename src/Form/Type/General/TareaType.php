<?php

namespace App\Form\Type\General;

use App\Entity\General\GenTarea;
use App\Entity\General\GenTareaPrioridad;
use App\Entity\Seguridad\Usuario;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TareaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titulo',TextType::class,['required' => true])
            ->add('usuarioRecibeRel',EntityType::class,[
                'class' => Usuario::class,
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('er')
                        ->orderBy('er.username');
                },
                'choice_label' => 'username',
                'required' => true
            ])
            ->add('descripcion',TextareaType::class,['required' => true])
            ->add('tareaPrioridadRel',EntityType::class,[
                'class' => GenTareaPrioridad::class,
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('er')
                        ->orderBy('er.nombre');
                },'choice_label' => 'nombre',
                'required' => true
            ])
            ->add('guardar',SubmitType::class,['label' => 'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => GenTarea::class,
        ]);
    }

}
