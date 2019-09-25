<?php

namespace App\Form\Type\RecursoHumano;

use App\Entity\RecursoHumano\RhuCredito;
use App\Entity\RecursoHumano\RhuCreditoPago;
use App\Entity\RecursoHumano\RhuCreditoPagoTipo;
use App\Entity\RecursoHumano\RhuCreditoTipo;
use App\Entity\RecursoHumano\RhuIncidente;
use App\Entity\RecursoHumano\RhuIncidenteTipo;
use App\Entity\RecursoHumano\RhuInduccion;
use App\Entity\RecursoHumano\RhuPermiso;
use App\Entity\RecursoHumano\RhuPermisoTipo;
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
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IncidenteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoEmpleadoFk', TextType::class, ['required' => true])
            ->add('incidenteTipoRel', EntityType::class, [
                'class' => RhuIncidenteTipo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('it')
                        ->orderBy('it.nombre', 'ASC');
                }, 'required' => true,
                'choice_label' => 'nombre',
                'attr' => ['class' => 'form-control to-select-2']
            ])
            ->add('nombreReporta', TextType::class, array('required' => false))
            ->add('cargoReporta', TextType::class, array('required' => false))
            ->add('fechaNovedad', DateType::class)
            ->add('fechaHoraNotificacion', DateTimeType::class)
            ->add('fechaHoraCitacionDescargo', DateTimeType::class)
            ->add('reportaSupervisor', CheckboxType::class,array('required'=>false))
            ->add('estadoPresentaDescargo', CheckboxType::class,array('required'=>false, 'label'=>'Presenta descargo'))
            ->add('estadoCitado', CheckboxType::class,array('required'=>false))
            ->add('faltaReglamento', CheckboxType::class,array('required'=>false,'label'=> ' '))
            ->add('reiteraFalta', CheckboxType::class,array('required'=>false,'label'=> ' '))
            ->add('elementosAdjuntos', CheckboxType::class,array('required'=>false,'label'=> ' '))
            ->add('impactaCliente', CheckboxType::class,array('required'=>false,'label'=> ' '))
            ->add('impactaEmpresa', CheckboxType::class,array('required'=>false,'label'=> ' '))
            ->add('capacitacion', CheckboxType::class,array('required'=>false,'label'=> ' '))
            ->add('comentarios', TextareaType::class, array('required' => false))
            ->add('descripcion', TextareaType::class, array('required' => false,'attr' => ['rows' => '7']))
            ->add('guardar', SubmitType::class, ['attr' => ['class' => 'btn btn-sm btn-primary']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RhuIncidente::class,
        ]);
    }

}
