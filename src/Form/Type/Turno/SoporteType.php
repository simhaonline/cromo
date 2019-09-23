<?php

namespace App\Form\Type\Turno;

use App\Entity\Turno\TurSoporte;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SoporteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('grupoRel', EntityType::class, [
                'required' => true,
                'class' => 'App\Entity\RecursoHumano\RhuGrupo',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('g')
                        ->where('g.cargarSoporte = 1')
                        ->orderBy('g.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'nombre:'
            ])
            ->add('fechaDesde', DateType::class, ['widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)])
            ->add('fechaHasta', DateType::class, ['widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)])
            ->add('dia31SoloExtra', CheckboxType::class, ['required' => false])
            ->add('comentario', TextareaType::class, array('required' => false))
            ->add('guardar', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TurSoporte::class,
        ]);
    }

}
