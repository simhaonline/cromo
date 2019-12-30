<?php

namespace App\Form\Type\Turno;

use App\Entity\Turno\TurPedido;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PedidoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('sectorRel', EntityType::class, [
                'required' => true,
                'class' => 'App\Entity\Turno\TurSector',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('s')
                        ->orderBy('s.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'nombre:'
            ])
            ->add('pedidoTipoRel', EntityType::class, [
                'required' => true,
                'class' => 'App\Entity\Turno\TurPedidoTipo',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('pt')
                        ->orderBy('pt.codigoPedidoTipoPk', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Tipo pedido:'
            ])
            ->add('fecha', DateType::class, array('widget' => 'single_text', 'format' => 'yyyy-MM-dd'))
            ->add('estrato', NumberType::class, array('required' => false))
            ->add('vrSalarioBase', NumberType::class)
            ->add('comentario', TextareaType::class, array('required' => false))
            ->add('guardar', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TurPedido::class,
        ]);
    }

}
