<?php

namespace App\Form\Type\Inventario;

use App\Entity\General\GenMoneda;
use App\Entity\Inventario\InvOrden;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrdenType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ordenTipoRel',EntityType::class,[
                'class' => 'App\Entity\Inventario\InvOrdenTipo',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('ot')
                        ->orderBy('ot.nombre','DESC');
                },
                'choice_label' => 'nombre',
                'label' => 'Tipo: '
            ])
            ->add('monedaRel',EntityType::class,[
                'required' => true,
                'class' => GenMoneda::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('m')
                        ->orderBy('m.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Moneda:'
            ])
            ->add('fechaEntrega',DateType::class,['required' => false])
            ->add('soporte',TextType::class,['required' => false])
            ->add('comentarios',TextareaType::class,['required' => false,'attr' => ['rows' => '5']])
            ->add('guardar',SubmitType::class,['label' => 'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']])
            ->add('guardarnuevo',SubmitType::class,['label' => 'Guardar y nuevo','attr' => ['class' => 'btn btn-sm btn-primary']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => InvOrden::class,
        ]);
    }


}
