<?php

namespace App\Form\Type\RecursoHumano;

use App\Entity\RecursoHumano\RhuCredito;
use App\Entity\RecursoHumano\RhuCreditoPago;
use App\Entity\RecursoHumano\RhuCreditoPagoTipo;
use App\Entity\RecursoHumano\RhuCreditoTipo;
use App\Entity\RecursoHumano\RhuVisita;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VisitaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoEmpleadoFk', TextType::class, ['required' => true])
            ->add('fechaInicio', DateType::class, ['widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)])
            ->add('fechaFinalizacion', DateType::class, ['widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)])
            ->add('fechaCredito', DateType::class, ['widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)])
            ->add('numeroCuotas', IntegerType::class, ['required' => true])
            ->add('comentario', TextareaType::class, ['required' => false])
            ->add('vrCredito',NumberType::class,['required' => false])
            ->add('vrCuota',NumberType::class,['required' => false])
            ->add('numeroCuotaActual',NumberType::class,['required' => false])
            ->add('validarCuotas',CheckboxType::class,['required' => false])
            ->add('aplicarCuotaPrima',CheckboxType::class,['required' => false])
            ->add('inactivoPeriodo',CheckboxType::class,['required' => false])
            ->add('estadoSuspendido',CheckboxType::class,['required' => false])
            ->add('aplicarCuotaCesantia',CheckboxType::class,['required' => false])
            ->add('creditoPagoTipoRel',EntityType::class,[
                'class' => RhuCreditoPagoTipo::class,
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('cpt')
                        ->orderBy('cpt.nombre');
                },'required' => true,
                'choice_label' => 'nombre'
            ])
            ->add('creditoTipoRel',EntityType::class,[
                'class' => RhuCreditoTipo::class,
                'query_builder' => function (EntityRepository $er){
                    return $er->createQueryBuilder('er')
                        ->orderBy('er.nombre','ASC');
                },'required' => true,
                'choice_label' => 'nombre'
            ])
            ->add('guardar', SubmitType::class, ['attr' => ['class' => 'btn btn-sm btn-primary']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RhuVisita::class,
        ]);
    }

}
