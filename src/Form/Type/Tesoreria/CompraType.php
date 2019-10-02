<?php

namespace App\Form\Type\Tesoreria;

use App\Entity\General\GenCuenta;
use App\Entity\Tesoreria\TesCompra;
use App\Entity\Tesoreria\TesCompraTipo;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompraType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('compraTipoRel', EntityType::class, [
                'class' => TesCompraTipo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('et')
                        ->orderBy('et.nombre', 'ASC');
                },
                'choice_label' => 'nombre'
            ])
            ->add('fecha', DateType::class, array('widget' => 'single_text', 'format' => 'yyyy-MM-dd'))
            ->add('comentarios', TextareaType::class, ['label' => 'Comentario:', 'required' => false])
            ->add('guardar', SubmitType::class, ['label' => 'Guardar', 'attr' => ['class' => 'btn btn-sm btn-primary']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TesCompra::class,
        ]);
    }

}