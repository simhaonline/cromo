<?php

namespace App\Form\Type\Cartera;

use App\Entity\Cartera\CarAnticipoTipo;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnticipoTipoType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoAnticipoTipoPk',TextType::class, ['required' => true, 'label' => 'nombre'])
            ->add('nombre',TextType::class, ['required' => true, 'label' => 'nombre'])
            ->add('consecutivo', NumberType::class, ['label' => 'Consecutivo','required' => true])
            ->add('orden', IntegerType::class)
            ->add('btnGuardar', SubmitType::class, ['label' => 'Guardar']);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CarAnticipoTipo::class,
        ]);
    }

}
