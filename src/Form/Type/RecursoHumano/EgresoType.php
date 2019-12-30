<?php

namespace App\Form\Type\RecursoHumano;

use App\Entity\General\GenCuenta;
use App\Entity\RecursoHumano\RhuEgreso;
use App\Entity\RecursoHumano\RhuEgresoTipo;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EgresoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fechaTrasmision', DateType::class, array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('fechaAplicacion', DateType::class, array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('nombre',TextType::class,[
                'required' => false,
                'attr' => ['placeholder' => 'Opcional']
            ])
            ->add('comentario',TextareaType::class,[
                'attr' => ['rows' => '6'],
                'required' => false
            ])
            ->add('egresoTipoRel',EntityType::class,[
                'class' => RhuEgresoTipo::class,
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('e')
                        ->orderBy('e.nombre');
                },'choice_label' => 'nombre',
                'required' => true,
                'attr' => ['class' => 'to-select-2']
            ])
            ->add('cuentaRel',EntityType::class,[
                'class' => GenCuenta::class,
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('e')
                        ->orderBy('e.nombre');
                },'choice_label' => 'nombre',
                'required' => true,
                'attr' => ['class' => 'to-select-2']
            ])
            ->add('guardar',SubmitType::class,['label' => 'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RhuEgreso::class,
        ]);
    }

}