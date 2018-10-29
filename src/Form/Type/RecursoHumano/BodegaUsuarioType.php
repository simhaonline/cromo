<?php

namespace App\Form\Type\RecursoHumano;

use App\Entity\Inventario\InvBodega;
use App\Entity\Inventario\InvBodegaUsuario;
use App\Entity\Seguridad\Usuario;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BodegaUsuarioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('usuarioRel',EntityType::class,[
                'class' => Usuario::class,
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('er')
                        ->orderBy('er.username');
                },'required' => true,
                'choice_label' => 'username'
            ])
            ->add('bodegaRel',EntityType::class,[
                'class' => InvBodega::class,
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('er')
                        ->orderBy('er.nombre');
                },'required' => true,
                'choice_label' => 'nombre'
            ])
            ->add('guardar',SubmitType::class,['attr' => ['class' => 'btn btn-sm btn-primary']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => InvBodegaUsuario::class,
        ]);
    }
}
