<?php

namespace App\Form\Type\Transporte;

use App\Entity\Transporte\TteCiudad;
use App\Entity\Transporte\TteEmpaque;
use App\Entity\Transporte\TteFrecuencia;
use App\Entity\Transporte\TteGuiaTipo;
use App\Entity\Transporte\TteRuta;
use App\Entity\Transporte\TteServicio;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

class FrecuenciaType extends AbstractType
{

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ciudadOrigenRel', EntityType::class, array(
                'class' => TteCiudad::class,
                'required' => true,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'attr' => ['class' => 'form-control to-select-2']
            ))
            ->add('ciudadDestinoRel', EntityType::class, array(
                'class' => TteCiudad::class,
                'required' => true,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'attr' => ['class' => 'form-control to-select-2']
            ))
            ->add('lunes', CheckboxType::class, ['required' => false, 'label' => 'Lunes'])
            ->add('martes', CheckboxType::class, ['required' => false, 'label' => 'Martes'])
            ->add('miercoles', CheckboxType::class, ['required' => false, 'label' => 'Miercoles'])
            ->add('jueves', CheckboxType::class, ['required' => false, 'label' => 'Jueves'])
            ->add('viernes', CheckboxType::class, ['required' => false, 'label' => 'Viernes'])
            ->add('sabado', CheckboxType::class, ['required' => false, 'label' => 'Sabado'])
            ->add('domingo', CheckboxType::class, ['required' => false, 'label' => 'Domingo'])
            ->add('guardar', SubmitType::class, ['label' => 'Guardar', 'attr' => ['class' => 'btn btn-sm btn-primary']]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => TteFrecuencia::class
        ));
    }

}
