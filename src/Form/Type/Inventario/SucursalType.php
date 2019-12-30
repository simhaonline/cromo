<?php

namespace App\Form\Type\Inventario;

use App\Entity\General\GenCiudad;
use App\Entity\Inventario\InvSucursal;
use App\Entity\Inventario\InvTercero;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SucursalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoSucursalPk', TextType::class, ['label' => 'Codigo sucursal: ', 'required' => true])
            ->add('nombre', TextType::class, ['label' => 'Nombre: ', 'required' => true])
            ->add('direccion', TextType::class, ['label' => 'Dirección: ', 'required' => true])
            ->add('contacto', TextType::class, ['label' => 'Contacto: ', 'required' => false])
            ->add('telefono', TextType::class, ['label' => 'Telefono: ', 'required' => false])
            ->add('terceroRel', EntityType::class, ['class' => InvTercero::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('it')
                        ->orderBy('it.nombreCorto','ASC');
                },
                'choice_label' => 'nombreCorto',
                'label' => 'Tercero: ',
                'attr' => ['class' => 'to-select-2']
            ])
            ->add('ciudadRel', EntityType::class, ['class' => GenCiudad::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.nombre','ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Ciudad: ',
                'attr' => ['class' => 'to-select-2']
            ])
            ->add('guardar', SubmitType::class, ['label' => 'Guardar', 'attr' => ['class' => 'btn btn-sm btn-primary']])
            ->add('guardarnuevo', SubmitType::class, ['label' => 'Guardar y nuevo', 'attr' => ['class' => 'btn btn-sm btn-primary']]);
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => InvSucursal::class,
        ]);
    }

}
