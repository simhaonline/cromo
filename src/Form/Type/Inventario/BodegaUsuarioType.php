<?php

namespace App\Form\Type\Inventario;

use App\Entity\Inventario\InvBodega;
use App\Entity\Inventario\InvBodegaUsuario;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BodegaUsuarioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoDocumentoPk',TextType::class,['label' => 'Codigo:','required' => true])
            ->add('nombre',TextType::class,['label' => 'Nombre: '])
            ->add('bodegaRel',EntityType::class,[
                'required' => true,
                'class' => InvBodega::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('b')
                        ->orderBy('b.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'bodega:'
            ])
            ->add('usuario')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => InvBodegaUsuario::class,
        ]);
    }

}
