<?php

namespace App\Form\Type\Turno;

use App\Entity\Turno\TurContratoDetalle;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TurContratoDetalleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('contratoConceptoRel', EntityType::class, [
                'required' => true,
                'class' => 'App\Entity\Turno\TurContratoConcepto',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('coc')
                        ->orderBy('coc.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'nombre:'
            ])
            ->add('contratoModalidadRel', EntityType::class, [
                'required' => true,
                'class' => 'App\Entity\Turno\TurContratoModalidad',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('com')
                        ->orderBy('com.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'nombre:'
            ])
            ->add('contratoConceptoFacturacionRel', EntityType::class, [
                'required' => true,
                'class' => 'App\Entity\Turno\TurContratoConcepto',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('coc')
                        ->orderBy('coc.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'nombre:'
            ])
            ->add('puestoRel', EntityType::class, [
                'required' => true,
                'class' => 'App\Entity\Turno\TurPuesto',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('p')
                        ->orderBy('p.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'nombre:'
            ])
            ->add('cantidad', NumberType::class)
            ->add('vrPrecioAjustado', NumberType::class, array('required' => false))
            ->add('porcentajeBaseIva', NumberType::class)
            ->add('detalleFactura', TextType::class, array('required' => false))
            ->add('horaInicio', TimeType::class)
            ->add('horaFin', TimeType::class)
            ->add('lunes', CheckboxType::class, array('required' => false))
            ->add('martes', CheckboxType::class, array('required' => false))
            ->add('miercoles', CheckboxType::class, array('required' => false))
            ->add('jueves', CheckboxType::class, array('required' => false))
            ->add('viernes', CheckboxType::class, array('required' => false))
            ->add('sabado', CheckboxType::class, array('required' => false))
            ->add('domingo', CheckboxType::class, array('required' => false))
            ->add('festivo', CheckboxType::class, array('required' => false))
            ->add('compuesto', CheckboxType::class, array('required' => false))
            ->add('vrSalarioBase', NumberType::class)
            ->add('fechaDesde', DateType::class, ['widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)])
            ->add('fechaHasta', DateType::class, ['widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)])
            ->add('liquidarDiasReales', CheckboxType::class, ['required' => false])
            ->add('dia31', CheckboxType::class, ['required' => false, 'label' => 'Habilitar dia 31'])
            ->add('noFacturar', CheckboxType::class, ['required' => false])
            ->add('facturaDistribuida', CheckboxType::class, ['required' => false])
            ->add('guardar', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TurContratoDetalle::class,
        ]);
    }
}

