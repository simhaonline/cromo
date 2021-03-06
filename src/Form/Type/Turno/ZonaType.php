<?php


namespace App\Form\Type\Turno;


use App\Entity\Turno\TurZona;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ZonaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoZonaPk', TextType::class, ['required' => true, 'label'=>'Nombre'])
            ->add('nombre', TextType::class, ['required' => true, 'label'=>'Nombre'])
            ->add('btnGuardar', SubmitType::class, array('label' => 'Guardar',))
            ->getForm();
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TurZona::class,
        ]);
    }

}