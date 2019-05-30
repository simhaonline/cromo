<?php

namespace App\Form\Type\Transporte;

use App\Entity\Transporte\TteCliente;
use App\Entity\Transporte\TteCondicionManejo;
use App\Entity\Transporte\TteDescuentoZona;
use App\Entity\Transporte\TteOperacion;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CondicionManejoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ciudadOrigenRel',EntityType::class,[
                'class' => 'App\Entity\Transporte\TteCiudad',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Origen:',
                'required' => false,
            ])
            ->add('ciudadDestinoRel',EntityType::class,[
                'class' => 'App\Entity\Transporte\TteCiudad',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Destino:',
                'required' => false,
            ])
            ->add('zonaRel',EntityType::class,[
                'class' => 'App\Entity\Transporte\TteZona',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('z')
                        ->orderBy('z.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Zona:',
                'required' => false,
            ])
            ->add('porcentaje',NumberType::class,['required' => true,'label' => 'Porcentaje:'])
            ->add('minimoUnidad',NumberType::class,['required' => true,'label' => 'Descuento:'])
            ->add('minimoDespacho',NumberType::class,['required' => true,'label' => 'Descuento:'])
            ->add('guardar', SubmitType::class, ['label'=>'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TteCondicionManejo::class,
        ]);
    }
}
