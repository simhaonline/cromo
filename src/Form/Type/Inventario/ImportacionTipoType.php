<?php

namespace App\Form\Type\Inventario;

use App\Entity\Inventario\InvImportacionTipo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImportacionTipoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoImportacionTipoPk', TextType::class, ['required' => true, 'label' => 'nombre'])
   //         ->add('codigoComprobanteFk', TextType::class, ['label' => 'comprobante','required' => false])
   //         ->add('prefijo', TextType::class, ['label' => 'prefijo','required' => false])
            ->add('nombre', TextType::class, ['required' => true, 'label' => 'nombre'])
            ->add('consecutivo', NumberType::class, ['label' => 'Consecutivo','required' => true])
            ->add('btnGuardar', SubmitType::class, ['label' => 'Guardar']);
    //        ->add('guardarnuevo', SubmitType::class, ['label'=>'Guardar y nuevo','attr' => ['class' => 'btn btn-sm btn-primary']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => InvImportacionTipo::class,
        ]);
    }

}
