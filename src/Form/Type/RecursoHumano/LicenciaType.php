<?php


namespace App\Form\Type\RecursoHumano;


use App\Entity\RecursoHumano\RhuLicencia;
use App\Entity\RecursoHumano\RhuLicenciaTipo;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LicenciaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoEmpleadoFk', TextType::class, array('required' => true))
            ->add('licenciaTipoRel', EntityType::class, array(
                'class' => RhuLicenciaTipo::class,
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('lt')
                        ->orderBy('lt.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => true))
            ->add('fechaDesde', DateType::class, ['required' => true, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => ['class' => 'date',]])
            ->add('fechaHasta', DateType::class, ['required' => true, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => ['class' => 'date',]])
            ->add('estadoCobrar', CheckboxType::class, array('required'  => false, 'label' => "Cobrar entidad"))
            ->add('estadoCobrarCliente', CheckboxType::class, array('required'  => false, 'label' => "Cobrar cliente"))
            ->add('afectaTransporte', CheckboxType::class, array('required'  => false, 'label' => "Afecta transporte"))
            ->add('estadoTranscripcion', CheckboxType::class, array('required'  => false, 'label' => "Por transcripcion"))
            ->add('estadoProrroga', CheckboxType::class, array('required'  => false, 'label' => "Prorroga"))
            ->add('pagarEmpleado', CheckboxType::class, array('required'  => false, 'label' => "Pagar empleado"))
            ->add('aplicarAdicional', CheckboxType::class, array('required'  => false, 'label' => "Aplicar adicional"))
            ->add('fechaAplicacion', DateType::class, ['required' => true, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => ['class' => 'date',]])
            ->add('comentarios', TextareaType::class, array('required' => false))
            ->add('vrIbcPropuesto', IntegerType::class, array('required' => false))
            ->add('vrPropuesto', IntegerType::class, array('required' => false))
            ->add('guardar', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RhuLicencia::class,
        ]);
    }
}