<?php


namespace App\Form\Type\RecursoHumano;


use App\Entity\RecursoHumano\RhuAcreditacion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AcreditacionAcreditarType  extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('estadoAcreditado', CheckboxType::class)
            ->add('fechaVencimiento', DateType::class, array('data'=> new \DateTime('now'), 'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('BtnEliminar', SubmitType::class, array('label'  => 'Eliminar acreditacion'))
            ->add('BtnGuardar', SubmitType::class, array('label'  => 'Guardar'));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RhuAcreditacion::class,
        ]);
    }
}