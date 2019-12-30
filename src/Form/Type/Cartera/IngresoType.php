<?php

namespace App\Form\Type\Cartera;

use App\Entity\Cartera\CarIngreso;
use App\Entity\Cartera\CarRecibo;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class IngresoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('cuentaRel',EntityType::class,[
                'required' => false,
                'class' => 'App\Entity\General\GenCuenta',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Cuenta:',
                'required' => true
            ])
            ->add('ingresoTipoRel',EntityType::class,[
                'required' => false,
                'class' => 'App\Entity\Cartera\CarIngresoTipo',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('it')
                        ->orderBy('it.orden', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Tipo ingreso:',
                'required' => true
            ])
            ->add('fechaPago', DateType::class, array('widget' => 'single_text', 'format' => 'yyyy-MM-dd'))
            ->add('comentarios',TextareaType::class,['required' => false,'label' => 'Comentarios:'])
            ->add('guardar', SubmitType::class, ['label'=>'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CarIngreso::class,
        ]);
    }

}
