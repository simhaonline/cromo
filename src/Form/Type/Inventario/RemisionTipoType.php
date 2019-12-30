<?php

namespace App\Form\Type\Inventario;

use App\Entity\Inventario\InvRemisionTipo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RemisionTipoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoRemisionTipoPk',TextType::class,['label' => 'Código remision tipo: '])
            ->add('nombre',TextType::class,['label' => 'Nombre: '])
            ->add('consecutivo',NumberType::class,['label' => 'Consecutivo:'])
            ->add('adicionar')
            ->add('adicionarPedido')
            ->add('adicionarRemision')
            ->add('orden', IntegerType::class)
            ->add('guardar',SubmitType::class,['label' => 'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => InvRemisionTipo::class,
        ]);
    }

}
