<?php

namespace App\Form\Type\Transporte;

use App\Entity\Transporte\TtePoseedor;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PoseedorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('identificacionRel',EntityType::class,[
                'required' => true,
                'class' => 'App\Entity\General\GenIdentificacion',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('i')
                        ->orderBy('i.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Identificacion tipo:'
            ])
            ->add('ciudadRel',EntityType::class,[
                'required' => true,
                'class' => 'App\Entity\General\GenCiudad',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Ciudad:'
            ])
            ->add('numeroIdentificacion',NumberType::class,['required' => true,'label' => 'Numero identificacion:'])
            ->add('correo',TextType::class,['required' => false,'label' => 'Nombre corto:'])
            ->add('nombre1',TextType::class,['required' => true,'label' => 'Primer nombre:'])
            ->add('nombre2',TextType::class,['required' => false,'label' => 'Segundo nombre:'])
            ->add('apellido1',TextType::class,['required' => false,'label' => 'Primer apellido:'])
            ->add('apellido2',TextType::class,['required' => false,'label' => 'Segundo apellido:'])
            ->add('direccion',TextType::class,['required' => true,'label' => 'Direccion:'])
            ->add('telefono',TextType::class,['required' => false,'label' => 'Telefono:'])
            ->add('movil',TextType::class,['required' => false,'label' => 'Celular:'])
            ->add('retencionFuente', CheckboxType::class, array('required' => false))
            ->add('retencionIndustriaComercio', CheckboxType::class, array('required' => false))
            ->add('guardar', SubmitType::class, ['label'=>'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']])
            ->add('guardarnuevo', SubmitType::class, ['label'=>'Guardar y nuevo','attr' => ['class' => 'btn btn-sm btn-primary']]);;
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TtePoseedor::class,
        ]);
    }

}
