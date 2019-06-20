<?php


namespace App\Form\Type\Turno;


use App\Entity\Turno\TurTurno;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TurnoType extends   AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoTurnoPk', TextType::class, ['required' => true])
            ->add('nombre', TextType::class, ['required' => true])
            ->add('horaDesde', TimeType::class, ['required' => true])
            ->add('horaHasta', TimeType::class, ['required' => true])
            ->add('horas', NumberType::class, ['required' => true])
            ->add('horasDiurnas', NumberType::class, ['required' => false])
            ->add('horasNocturnas', NumberType::class, ['required' => false])
            ->add('novedad', CheckboxType::class, ['required' => false,'label' => 'Novedad'])
            ->add('descanso', CheckboxType::class, ['required' => false,'label' => 'Descanso'])
            ->add('incapacidad', CheckboxType::class, ['required' => false,'label' => 'Incapacidad'])
            ->add('licencia', CheckboxType::class, ['required' => false,'label' => 'Licencia'])
            ->add('vacacion', CheckboxType::class, ['required' => false,'label' => 'Vacacion'])
            ->add('ingreso', CheckboxType::class, ['required' => false,'label' => 'Ingreso'])
            ->add('induccion', CheckboxType::class, ['required' => false,'label' => 'Induccion'])
            ->add('ausentismo', CheckboxType::class, ['required' => false,'label' => 'Ausentismo'])
            ->add('dia', CheckboxType::class, ['required' => false,'label' => 'Dia'])
            ->add('noche', CheckboxType::class, ['required' => false,'label' => 'Noche'])
            ->add('guardar', SubmitType::class, array('label' => 'Guardar',))
            ->getForm();    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TurTurno::class,
        ]);
    }
}