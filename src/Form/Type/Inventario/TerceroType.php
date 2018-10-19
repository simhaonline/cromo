<?php

namespace App\Form\Type\Inventario;

use App\Entity\Inventario\InvTercero;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TerceroType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ciudadRel', EntityType::class, [
                'class' => 'App\Entity\General\GenCiudad',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.nombre');
                },
                'choice_label' => 'nombre',
                'required' => false,
                'attr' => ['class' => 'form-control to-select-2']
            ])
            ->add('precioCompraRel', EntityType::class, [
                'class' => 'App\Entity\Inventario\InvPrecio',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('pc')
                        ->where('pc.compra = 1')
                        ->orderBy('pc.nombre');
                },
                'choice_label' => 'nombre',
                'required' => false
            ])
            ->add('precioVentaRel', EntityType::class, [
                'class' => 'App\Entity\Inventario\InvPrecio',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('pv')
                        ->where('pv.venta = 1')
                        ->orderBy('pv.nombre');
                },
                'choice_label' => 'nombre',
                'required' => false
            ])
            ->add('formaPagoRel', EntityType::class, [
                'class' => 'App\Entity\General\GenFormaPago',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('fp')
                        ->orderBy('fp.nombre');
                },
                'choice_label' => 'nombre',
                'required' => true
            ])
            ->add('digitoVerificacion', TextType::class, ['required' => false, 'attr' => ['class' => 'form-control']])
            ->add('numeroIdentificacion', TextType::class, ['required' => true, 'attr' => ['class' => 'form-control']])
            ->add('nombreCorto', TextType::class, ['required' => true, 'attr' => ['class' => 'form-control']])
            ->add('nombres', TextType::class, ['required' => false, 'attr' => ['class' => 'form-control']])
            ->add('apellido1', TextType::class, ['required' => false, 'attr' => ['class' => 'form-control']])
            ->add('apellido2', TextType::class, ['required' => false, 'attr' => ['class' => 'form-control']])
            ->add('plazoPago', TextType::class, ['required' => true, 'attr' => ['class' => 'form-control']])
            ->add('direccion', TextType::class, ['required' => true, 'attr' => ['class' => 'form-control']])
            ->add('telefono', TextType::class, ['required' => true, 'attr' => ['class' => 'form-control']])
            ->add('celular', TextType::class, ['required' => false, 'attr' => ['class' => 'form-control']])
            ->add('email', TextType::class, ['required' => false, 'attr' => ['class' => 'form-control']])
            ->add('cliente', CheckboxType::class, ['required' => false, 'label' => ' '])
            ->add('proveedor', CheckboxType::class, ['required' => false, 'label' => ' '])
            ->add('retencionIva', CheckboxType::class, ['required' => false])
            ->add('retencionFuente', CheckboxType::class, ['required' => false])
            ->add('retencionFuenteSinBase', CheckboxType::class, ['required' => false])
            ->add('guardar', SubmitType::class, ['label' => 'Guardar', 'attr' => ['class' => 'btn btn-sm btn-primary']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => InvTercero::class,
        ]);
    }
}
