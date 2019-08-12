<?php


namespace App\Form\Type\RecursoHumano;


use App\Entity\RecursoHumano\RhuIncapacidad;
use App\Entity\RecursoHumano\RhuIncapacidadTipo;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IncapacidadType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('incapacidadTipoRel', EntityType::class, array(
                'class' => RhuIncapacidadTipo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('it')
                        ->orderBy('it.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'required' => true))
            ->add('numeroEps', TextType::class, array('required' => true))
            ->add('vrPropuesto', NumberType::class, array('required' => false))
            ->add('vrIbcPropuesto', NumberType::class)
            ->add('codigoIncapacidadProrrogaFk', NumberType::class, array('required' => false))
            ->add('estadoTranscripcion', CheckboxType::class, array('required'  => false, 'label' => "Por transcripcion"))
            ->add('estadoCobrar', CheckboxType::class, array('required'  => false, 'label' => "Cobrar"))
            ->add('estadoCobrarCliente', CheckboxType::class, array('required'  => false, 'label' => "Cobrar al cliente"))
            ->add('estadoProrroga', CheckboxType::class, array('required'  => false, 'label' => "Prorroga"))
            ->add('pagarEmpleado', CheckboxType::class, array('required'  => false, 'label' => "Pagar al empleado"))
            ->add('aplicarAdicional', CheckboxType::class, array('required'  => false, 'label' => "Aplicar adicional"))
            ->add('fechaAplicacion', DateType::class, ['required' => true, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => ['class' => 'date',]])
            ->add('fechaDesde', DateType::class, ['required' => true, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => ['class' => 'date',]])
            ->add('fechaHasta', DateType::class, ['required' => true, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => ['class' => 'date',]])
            ->add('fechaDocumentoFisico', DateType::class, ['required' => true, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => ['class' => 'date',]])
            ->add('comentarios', TextareaType::class, array('required' => false))
            ->add('guardar', SubmitType::class)
            ->add('medico',TextType::class, array('required' => false));
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RhuIncapacidad::class,
        ]);
    }
}