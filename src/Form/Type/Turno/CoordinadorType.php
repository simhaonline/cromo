<?php


namespace App\Form\Type\Turno;


use App\Entity\Turno\TurCoordinador;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CoordinadorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoCoordinadorPk', TextType::class, ['required' => true, 'label'=>'Codigo'])
            ->add('nombre', TextType::class, ['required' => true, 'label'=>'Nombre'])
            ->add('numeroIdentificacion', TextType::class, ['required' => true, 'label'=>'Identificacion'])
            ->add('correo', EmailType::class, ['required' => true, 'label'=>'Correo'])
            ->add('celular', TextType::class, ['required' => true, 'label'=>'Celular'])
            ->add('btnGuardar', SubmitType::class, array('label' => 'Guardar'))
            ->getForm();
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TurCoordinador::class,
        ]);
    }

}