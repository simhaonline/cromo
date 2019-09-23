<?php

namespace App\Form\Type\RecursoHumano;

use App\Entity\RecursoHumano\RhuCredito;
use App\Entity\RecursoHumano\RhuCreditoPago;
use App\Entity\RecursoHumano\RhuCreditoPagoTipo;
use App\Entity\RecursoHumano\RhuCreditoTipo;
use App\Entity\RecursoHumano\RhuVisita;
use App\Entity\RecursoHumano\RhuVisitaTipo;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
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
            ->add('validarVencimiento', CheckboxType::class, array('required'  => false))
            ->add('comentarios', TextareaType::class, array('required' => false, 'attr' => array('cols' => '5', 'rows' => '25')))
            ->add('fecha', DateTimeType::class, array('required' => true))
            ->add('fechaVence', DateType::class, array('required' => true))
            ->add('nombreQuienVisita', TextType::class,array('required' => true))
            ->add('vrTotal', NumberType::class, array('required' => false))
            ->add('visitaTipoRel', EntityType::class, [
                'class' => RhuVisitaTipo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('vt')
                        ->orderBy('vt.nombre', 'ASC');
                }, 'required' => true,
                'choice_label' => 'nombre',
                'attr' => ['class' => 'form-control to-select-2']
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
