<?php

namespace App\Form\Type\Turno;

use App\Entity\Turno\TurConcepto;
use App\Entity\Turno\TurContratoDetalle;
use App\Entity\Turno\TurItem;
use App\Entity\Turno\TurModalidad;
use App\Entity\Turno\TurPuesto;
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

class ContratoDetalleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('itemRel', EntityType::class, [
                'required' => true,
                'class' => TurItem::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('i')
                        ->orderBy('i.orden', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'nombre:',
                'attr' => ['class' => 'form-control to-select-2']
            ])
            ->add('modalidadRel', EntityType::class, [
                'required' => true,
                'class' => TurModalidad::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('m')
                        ->orderBy('m.nombre', 'DESC');
                },
                'choice_label' => 'nombre',
                'label' => 'nombre:',
                'attr' => ['class' => 'form-control to-select-2']
            ])
            ->add('puestoRel', EntityType::class, [
                'required' => true,
                'class' => TurPuesto::class,
                'query_builder' => function (EntityRepository $er) use ($options) {
                    $cliente = $options['data']->getContratoRel()->getCodigoClienteFk();
                    return $er->createQueryBuilder('p')
                        ->where("p.codigoClienteFk = {$cliente}")
                        ->orderBy('p.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'nombre:',
                'attr' => ['class' => 'form-control to-select-2']
            ])
            ->add('cantidad', NumberType::class)
            ->add('vrPrecioAjustado', NumberType::class, array('required' => false))
            ->add('porcentajeBaseIva', NumberType::class, array('required' => false))
            ->add('lunes', CheckboxType::class, array('required' => false))
            ->add('martes', CheckboxType::class, array('required' => false))
            ->add('miercoles', CheckboxType::class, array('required' => false))
            ->add('jueves', CheckboxType::class, array('required' => false))
            ->add('viernes', CheckboxType::class, array('required' => false))
            ->add('sabado', CheckboxType::class, array('required' => false))
            ->add('domingo', CheckboxType::class, array('required' => false))
            ->add('festivo', CheckboxType::class, array('required' => false))
            ->add('compuesto', CheckboxType::class, array('required' => false))
            ->add('programar', CheckboxType::class, array('required' => false))
            ->add('cortesia', CheckboxType::class, array('required' => false))
            ->add('vrSalarioBase', NumberType::class)
            ->add('fechaDesde', DateType::class, ['widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)])
            ->add('fechaHasta', DateType::class, ['widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)])
            ->add('horaDesde', TimeType::class)
            ->add('horaHasta', TimeType::class)
            ->add('guardar', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TurContratoDetalle::class,
        ]);
    }
}

