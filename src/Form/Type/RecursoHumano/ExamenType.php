<?php

namespace App\Form\Type\RecursoHumano;

use App\Entity\RecursoHumano\RhuExamen;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExamenType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('examenClaseRel', EntityType::class, array(
                'required' => true,
                'class' => 'App\Entity\RecursoHumano\RhuExamenClase',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('ec')
                        ->orderBy('ec.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => ' '
            ))
            ->add('entidadExamenRel', EntityType::class, array(
                'required' => true,
                'class' => 'App\Entity\RecursoHumano\RhuEntidadExamen',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('ee')
                        ->orderBy('ee.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => ' '
            ))
            ->add('fecha', DateType::class, array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('comentario', TextareaType::class, array('required' => false))
            ->add('guardar', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RhuExamen::class,
        ]);
    }

}
