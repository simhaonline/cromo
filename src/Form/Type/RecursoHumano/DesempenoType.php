<?php


namespace App\Form\Type\RecursoHumano;


use App\Entity\RecursoHumano\RhuDesempeno;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DesempenoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoEmpleadoFk', TextType::class, ['required' => true])
            ->add('dependenciaEvaluado', TextType::class, array('required' => true))
            ->add('dependenciaEvalua', TextType::class, array('required' => true))
            ->add('jefeEvalua', TextType::class, array('required' => true))
            ->add('cargoJefeEvalua', TextType::class, array('required' => true))
            ->add('fecha', DateType::class, array('required' => true))
            ->add('guardar', SubmitType::class, ['attr' => ['class' => 'btn btn-sm btn-primary']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RhuDesempeno::class,
        ]);
    }


}