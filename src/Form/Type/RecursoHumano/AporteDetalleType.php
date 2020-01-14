<?php

namespace App\Form\Type\RecursoHumano;

use App\Entity\RecursoHumano\RhuAporte;
use App\Entity\RecursoHumano\RhuAporteDetalle;
use App\Entity\RecursoHumano\RhuAporteTipo;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AporteDetalleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('ingreso', TextType::class, array('required' => false))
            ->add('retiro', TextType::class, array('required' => false))
            ->add('ibcPension', NumberType::class, array('required' => false))
            ->add('ibcSalud', NumberType::class, array('required' => false))
            ->add('ibcOtrosParafiscalesDiferentesCcf', NumberType::class, array('required' => false))
            ->add('ibcRiesgosProfesionales', NumberType::class, array('required' => false))
            ->add('ibcCaja', NumberType::class, array('required' => false))
            ->add('diasCotizadosPension', NumberType::class, array('required' => false))
            ->add('diasCotizadosSalud', NumberType::class, array('required' => false))
            ->add('diasCotizadosRiesgosProfesionales', NumberType::class, array('required' => false))
            ->add('diasCotizadosCajaCompensacion', NumberType::class, array('required' => false))
            ->add('cotizacionPension', NumberType::class, array('required' => false))
            ->add('cotizacionSalud', NumberType::class, array('required' => false))
            ->add('cotizacionRiesgos', NumberType::class, array('required' => false))
            ->add('cotizacionCaja', NumberType::class, array('required' => false))
            ->add('cotizacionSena', NumberType::class, array('required' => false))
            ->add('cotizacionIcbf', NumberType::class, array('required' => false))
            ->add('aporteVoluntarioFondoPensionesObligatorias', NumberType::class, array('required' => false))
            ->add('entidadSaludRel', EntityType::class, array(
                'class' => 'App\Entity\RecursoHumano\RhuEntidad',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('es')
                        ->orderBy('es.nombre', 'ASC')
                        ->where('es.eps = 1');
                },
                'choice_label' => 'nombre',
                'required' => true))
            ->add('entidadPensionRel', EntityType::class, array(
                'class' => 'App\Entity\RecursoHumano\RhuEntidad',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('p')
                        ->orderBy('p.nombre', 'ASC')
                        ->where('p.pen = 1');
                },
                'choice_label' => 'nombre',
            ))
            ->add('entidadCajaRel', EntityType::class, array(
                'class' => 'App\Entity\RecursoHumano\RhuEntidad',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('ec')
                        ->orderBy('ec.nombre', 'ASC')
                        ->where('ec.ccf = 1');
                },
                'choice_label' => 'nombre',
                'required' => true))
            ->add('aportesFondoSolidaridadPensionalSolidaridad', NumberType::class, array('required' => false))
            ->add('aportesFondoSolidaridadPensionalSubsistencia', NumberType::class, array('required' => false))
            ->add('btnGuardar', SubmitType::class, array('label' => 'Guardar'));

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RhuAporteDetalle::class,
        ]);
    }

}