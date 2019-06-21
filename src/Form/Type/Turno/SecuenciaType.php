<?php


namespace App\Form\Type\Turno;


use App\Entity\Turno\TurSecuencia;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SecuenciaType extends   AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoSecuenciaPk', TextType::class, ['required' => true])
            ->add('nombre', TextType::class, ['required' => false])
            ->add('dia1', TextType::class, ['required' => false])
            ->add('dia2', TextType::class, ['required' => false])
            ->add('dia3', TextType::class, ['required' => false])
            ->add('dia4', TextType::class, ['required' => false])
            ->add('dia5', TextType::class, ['required' => false])
            ->add('dia6', TextType::class, ['required' => false])
            ->add('dia6', TextType::class, ['required' => false])
            ->add('dia7', TextType::class, ['required' => false])
            ->add('dia8', TextType::class, ['required' => false])
            ->add('dia9', TextType::class, ['required' => false])
            ->add('dia10', TextType::class, ['required' => false])
            ->add('dia11', TextType::class, ['required' => false])
            ->add('dia12', TextType::class, ['required' => false])
            ->add('dia12', TextType::class, ['required' => false])
            ->add('dia13', TextType::class, ['required' => false])
            ->add('dia14', TextType::class, ['required' => false])
            ->add('dia15', TextType::class, ['required' => false])
            ->add('dia16', TextType::class, ['required' => false])
            ->add('dia17', TextType::class, ['required' => false])
            ->add('dia18', TextType::class, ['required' => false])
            ->add('dia19', TextType::class, ['required' => false])
            ->add('dia20', TextType::class, ['required' => false])
            ->add('dia21', TextType::class, ['required' => false])
            ->add('dia22', TextType::class, ['required' => false])
            ->add('dia23', TextType::class, ['required' => false])
            ->add('dia24', TextType::class, ['required' => false])
            ->add('dia25', TextType::class, ['required' => false])
            ->add('dia26', TextType::class, ['required' => false])
            ->add('dia27', TextType::class, ['required' => false])
            ->add('dia28', TextType::class, ['required' => false])
            ->add('dia29', TextType::class, ['required' => false])
            ->add('dia30', TextType::class, ['required' => false])
            ->add('dia31', TextType::class, ['required' => false])
            ->add('lunes', TextType::class, ['required' => false])
            ->add('martes', TextType::class, ['required' => false])
            ->add('miercoles', TextType::class, ['required' => false])
            ->add('jueves', TextType::class, ['required' => false])
            ->add('viernes', TextType::class, ['required' => false])
            ->add('sabado', TextType::class, ['required' => false])
            ->add('domingo', TextType::class, ['required' => false])
            ->add('festivo', TextType::class, ['required' => false])
            ->add('domingoFestivo', TextType::class, ['required' => false])
            ->add('horas', IntegerType::class, ['required' => false])
            ->add('dias', IntegerType::class, ['required' => false])

            ->add('guardar', SubmitType::class, array('label' => 'Guardar',))
        ->getForm();
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TurSecuencia::class,
        ]);
    }
}