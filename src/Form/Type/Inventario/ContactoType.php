<?php

namespace App\Form\Type\Inventario;

use App\Entity\Inventario\InvContacto;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('cargo', TextType::class, ['label' => 'Cargo:','required' => true])
            ->add('area', TextType::class, ['label' => 'Area:','required' => true])
            ->add('telefono', TextType::class, ['label' => 'Telefono:','required' => true])
            ->add('celular', TextType::class, ['label' => 'Celular:','required' => true])
            ->add('numeroIdentificacion', TextType::class, ['label' => 'Numero identificacion:','required' => true])
            ->add('nombreCorto', TextType::class, ['label' => 'Nombre', 'required' => true])
            ->add('guardar', SubmitType::class, ['label'=>'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => InvContacto::class,
        ]);
    }

}
