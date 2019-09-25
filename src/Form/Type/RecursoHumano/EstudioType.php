<?php

namespace App\Form\Type\RecursoHumano;

use App\Entity\RecursoHumano\RhuCredito;
use App\Entity\RecursoHumano\RhuCreditoPago;
use App\Entity\RecursoHumano\RhuCreditoPagoTipo;
use App\Entity\RecursoHumano\RhuCreditoTipo;
use App\Entity\RecursoHumano\RhuEstudio;
use App\Entity\RecursoHumano\RhuEstudioTipo;
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

class EstudioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoEmpleadoFk', TextType::class, ['required' => true])
            ->add('estudioTipoRel', EntityType::class, [
                'class' => RhuEstudioTipo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('et')
                        ->orderBy('et.nombre', 'ASC');
                }, 'required' => true,
                'choice_label' => 'nombre',
                'attr' => ['class' => 'form-control to-select-2']
            ])
            ->add('ciudadRel', EntityType::class, [
                'required' => false,
                'class' => 'App\Entity\General\GenCiudad',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('gc')
                        ->orderBy('gc.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Ciudad:',
                'attr' => ['class' => 'form-control to-select-2']
            ])
            ->add('institucion', TextType::class, array('required' => false))
            ->add('fechaInicio', DateType::class, array('attr' => array(), 'required' => false, 'years' => range(date('1970'), date('Y') + 5)))
            ->add('fechaTerminacion', DateType::class, array('required' => false, 'years' => range(date('1970'), date('Y') + 5)))
            ->add('validarVencimiento', ChoiceType::class, array('choices' => array('SI' => '1', 'NO' => '0')))
            ->add('fechaVencimientoCurso', DateType::class, array('required' => false, 'years' => range(date('1970'), date('Y') + 5)))
            ->add('graduado', ChoiceType::class, array('choices' => array('SI' => '1', 'NO' => '0')))
            ->add('titulo', TextType::class, array('required' => false))
            ->add('comentarios', TextareaType::class, array('required' => false))
            ->add('numeroRegistro', TextType::class, array('required' => false))
            ->add('guardar', SubmitType::class, ['attr' => ['class' => 'btn btn-sm btn-primary']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RhuEstudio::class,
        ]);
    }

}
