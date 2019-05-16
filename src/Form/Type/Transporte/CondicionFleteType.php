<?php

namespace App\Form\Type\Transporte;

use App\Entity\Transporte\TteCliente;
use App\Entity\Transporte\TteCondicionFlete;
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

class CondicionFleteType extends AbstractType
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
            ->add('descuentoPeso',NumberType::class,['required' => true,'label' => 'Descuento:'])
            ->add('descuentoUnidad',NumberType::class,['required' => true,'label' => 'Descuento:'])
            ->add('pesoMinimo',NumberType::class,['required' => true,'label' => 'Descuento:'])
            ->add('pesoMinimoGuia',NumberType::class,['required' => true,'label' => 'Descuento:'])
            ->add('fleteMinimo',NumberType::class,['required' => true,'label' => 'Descuento:'])
            ->add('fleteMinimoGuia',NumberType::class,['required' => true,'label' => 'Descuento:'])
            ->add('guardar', SubmitType::class, ['label'=>'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TteCondicionFlete::class,
        ]);
    }
}
