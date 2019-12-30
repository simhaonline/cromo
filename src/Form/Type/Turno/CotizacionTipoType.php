<?php

namespace App\Form\Type\Turno;

use App\Entity\Turno\TurContratoTipo;
use App\Entity\Turno\TurCotizacionTipo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CotizacionTipoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoCotizacionTipoPk', TextType::class, ['required' => true, 'label' => 'Codigo cotizacion tipo Pk:'])
            ->add('nombre', TextType::class, ['required' => true, 'label' => 'Nombre:'])
            ->add('guardar', SubmitType::class, ['label'=>'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TurCotizacionTipo::class,
        ]);
    }

}
