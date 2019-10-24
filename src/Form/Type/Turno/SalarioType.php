<?php


namespace App\Form\Type\Turno;


use App\Entity\Turno\TurSalario;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SalarioType  extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('nombre', TextType::class, array('required' => false))
            ->add('codigoSalarioPk', TextType::class, array('required' => false))
            ->add('vrSalario', NumberType::class, array('required' => false))
            ->add('vrHoraDiurna', NumberType::class, array('required' => false))
            ->add('vrHoraNocturna', NumberType::class, array('required' => false))
            ->add('vrTurnoDia', NumberType::class, array('required' => false))
            ->add('vrTurnoNoche', NumberType::class, array('required' => false))
            ->add('guardar', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TurSalario::class,
        ]);
    }
}