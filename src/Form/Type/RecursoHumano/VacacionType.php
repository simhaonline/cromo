<?php

namespace App\Form\Type\RecursoHumano;

use App\Entity\RecursoHumano\RhuRequisitoTipo;
use App\Entity\RecursoHumano\RhuVacacion;
use App\Entity\RecursoHumano\RhuVacacionTipo;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VacacionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoLiquidacionRecargosFk', ChoiceType::class, [
                'choices' => array(
                    'PERIODO' => 'RN002', 'ULTIMO AÑO HASTA LA ULTIMA FECHA DE PAGO' => 'RN001', 'SIN RECARGOS' => 'RN00',
                ),
                'required'    => true,
            ])
            ->add('vacacionTipoRel',EntityType::class,[
                'class' => RhuVacacionTipo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('vt')
                        ->orderBy('vt.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'required' => true,
            ])
            ->add('fechaDesdeDisfrute', DateType::class, array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('fechaHastaDisfrute', DateType::class, array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('fechaInicioLabor', DateType::class, array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('diasDisfrutados',TextType::class,['required' => true])
            ->add('diasPagados',TextType::class,['required' => true])
            ->add('comentarios',TextareaType::class,['required' => false])
            ->add('vrSalarioPromedioPropuesto',TextType::class,['required' => true])
            ->add('vrDisfrutePropuesto',TextType::class,['required' => true])
            ->add('vrSalarioPromedioPropuestoPagado',TextType::class,['required' => true])
            ->add('vrSaludPropuesto',TextType::class,['required' => true])
            ->add('vrPensionPropuesto',TextType::class,['required' => true])
            ->add('guardar',SubmitType::class,['attr' => ['class' => 'btn btn-sm btn-primary']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RhuVacacion::class,
        ]);
    }

}
