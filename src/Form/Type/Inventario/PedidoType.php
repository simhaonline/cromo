<?php

namespace App\Form\Type\Inventario;

use App\Entity\Inventario\InvPedido;
use App\Entity\Inventario\InvPedidoTipo;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PedidoType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('pedidoTipoRel',EntityType::class,[
                'required' => true,
                'class' => InvPedidoTipo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('pt')
                        ->orderBy('pt.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Pedido tipo:'
            ])
            ->add('soporte',TextType::class, ['required' => false,'label' => 'Soporte:'])
            ->add('comentario',TextareaType::class, ['required' => false,'label' => 'Comentario:'])
            ->add('guardar', SubmitType::class, ['label'=>'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\Inventario\InvPedido'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'App_pedido';
    }

}
