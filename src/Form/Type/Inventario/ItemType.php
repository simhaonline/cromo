<?php

namespace App\Form\Type\Inventario;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ItemType extends AbstractType
{

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre', TextType::class, ['required' => false])
            ->add('codigoBarras', TextType::class, ['required' => false])
            ->add('porcentajeIva', IntegerType::class, ['required' => false])
            ->add('afectaInventario', CheckboxType::class, ['required' => false,'label' => 'Afecta inventario'])
            ->add('descripcion', TextareaType::class, ['required' => false])
            ->add('stockMinimo', IntegerType::class, ['required' => false])
            ->add('stockMaximo', IntegerType::class, ['required' => false])
            ->add('vrPrecioPredeterminado', IntegerType::class, ['required' => false])
            ->add('guardar', SubmitType::class, ['label' => 'Guardar'])
            ->add('guardarnuevo', SubmitType::class, ['label' => 'Guardar y nuevo']);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\Inventario\InvItem'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'App_item';
    }

}
