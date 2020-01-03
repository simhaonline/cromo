<?php

namespace App\Form\Type\Turno;

use App\Entity\Turno\TurFactura;
use App\Entity\Turno\TurPedido;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FacturaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('facturaTipoRel', EntityType::class, [
                'required' => true,
                'class' => 'App\Entity\Turno\TurFacturaTipo',
                'query_builder' => function (EntityRepository $er) use ($options) {
                    return $er->createQueryBuilder('ft')
                        ->orderBy('ft.nombre', 'ASC');
                },
                'choice_label' => 'nombre'
            ])
            ->add('plazoPago', NumberType::class, ['label' => 'Plazo pago', 'required' => false])
            ->add('soporte', TextType::class, ['label' => 'Soporte', 'required' => false])
            ->add('imprimirAgrupada', CheckboxType::class, array('required' => false))
            ->add('imprimirRelacion', CheckboxType::class, array('required' => false))
            ->add('tituloRelacion', TextType::class, ['label' => 'Titulo relacion', 'required' => false])
            ->add('descripcion', TextType::class, ['label' => 'Descripcion', 'required' => false])
            ->add('detalleRelacion', TextType::class, ['label' => 'Detalle relacion', 'required' => false])
            ->add('comentarios', TextareaType::class, array('required' => false))
            ->add('guardar', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TurFactura::class,
        ]);
    }

}
