<?php

namespace App\Form\Type\Documental;

use App\Entity\Documental\DocConfiguracion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConfiguracionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('rutaBandeja', TextType::class, ['required'=> false])
            ->add('rutaAlmacenamiento', TextType::class, ['required' => false])
            ->add('guardar',SubmitType::class,['label' => 'Actualizar','attr' => ['class' => 'btn btn-sm btn-primary']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DocConfiguracion::class,
        ]);
    }
}
