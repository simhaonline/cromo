<?php

namespace App\Form\Type\RecursoHumano;

use App\Entity\RecursoHumano\RhuCredito;
use App\Entity\RecursoHumano\RhuCreditoPago;
use App\Entity\RecursoHumano\RhuCreditoPagoTipo;
use App\Entity\RecursoHumano\RhuCreditoTipo;
use App\Entity\RecursoHumano\RhuDisciplinario;
use App\Entity\RecursoHumano\RhuDisciplinarioFalta;
use App\Entity\RecursoHumano\RhuDisciplinarioMotivo;
use App\Entity\RecursoHumano\RhuDisciplinarioTipo;
use App\Entity\RecursoHumano\RhuVisita;
use App\Entity\RecursoHumano\RhuVisitaTipo;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DisciplinarioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('disciplinarioTipoRel', EntityType::class, array(
                'class' => RhuDisciplinarioTipo::class,
                'choice_label' => 'nombre',
                'attr' => ['class' => 'form-control to-select-2']
            ))
            ->add('disciplinarioMotivoRel', EntityType::class, array(
                'class' => RhuDisciplinarioMotivo::class,
                'choice_label' => 'nombre',
                'required' => false,
                'attr' => ['class' => 'form-control to-select-2']
            ))
            ->add('disciplinariosFaltaRel', EntityType::class, array(
                'class' => RhuDisciplinarioFalta::class,
                'choice_label' => 'falta',
                'required' => false,
                'empty_data' => null,
                'attr' => ['class' => 'form-control to-select-2']
            ))
            ->add('codigoEmpleadoFk', TextType::class, ['required' => true])
            ->add('asunto', TextareaType::class, array('required' => false))
            ->add('diasSuspencion', TextType::class, array('required' => false))
            ->add('reentrenamiento', ChoiceType::class, array('choices' => array('NO' => '0', 'SI' => '1')))
            ->add('fechaNotificacion', DateTimeType::class, array('data' => new \DateTime('now')))
            ->add('fechaIncidente', DateType::class, array('format' => 'yyyyMMdd'))
            ->add('fechaDesdeSancion', DateType::class, array('format' => 'yyyyMMdd'))
            ->add('fechaHastaSancion', DateType::class, array('format' => 'yyyyMMdd'))
            ->add('fechaIngresoTrabajo', DateType::class, array('format' => 'yyyyMMdd'))
            ->add('estadoSuspendido', CheckboxType::class, array('required' => false))
            ->add('estadoProcede', CheckboxType::class, array('required' => false))
            ->add('comentarios', TextareaType::class, array('required' => false))
            ->add('guardar', SubmitType::class, ['attr' => ['class' => 'btn btn-sm btn-primary']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RhuDisciplinario::class,
        ]);
    }

}
