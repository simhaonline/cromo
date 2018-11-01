<?php

namespace App\Form\Type\Inventario;

use App\Entity\Inventario\InvMovimiento;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FacturaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('facturaTipoRel',EntityType::class,[
                'required' => true,
                'class' => 'App\Entity\Inventario\InvFacturaTipo',
                'query_builder' => function (EntityRepository $er) use ($options) {
                    return $er->createQueryBuilder('ft')
                        ->orderBy('ft.nombre', 'ASC');
                },
                'choice_label' => 'nombre'
            ])
            ->add('codigoTerceroFk',TextType::class,['required' => false ])
            ->add('soporte',TextType::class,['required' => false ])
            ->add('codigoSucursalFk',TextType::class,['required' => false ])
            ->add('plazoPago',IntegerType::class,['required' => false ])
            ->add('comentarios',TextareaType::class,['required' => false ,'attr' => ['class' => 'form-control','rows' => '5']])
            ->add('guardar',SubmitType::class,['label' => 'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => InvMovimiento::class,
        ]);
    }
}
