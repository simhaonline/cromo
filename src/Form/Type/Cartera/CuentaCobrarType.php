<?php

namespace App\Form\Type\Cartera;

use App\Entity\Cartera\CarCuentaCobrar;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CuentaCobrarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('asesorRel',EntityType::class,[
                'required' => false,
                'class' => 'App\Entity\General\GenAsesor',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('a')
                        ->orderBy('a.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Asesor:'
            ])
            ->add('cuentaCobrarTipoRel',EntityType::class,[
                'required' => true,
                'class' => 'App\Entity\Cartera\CarCuentaCobrarTipo',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('ct')
                        ->where('ct.saldoInicial = 1')
                        ->orderBy('ct.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Tipo cuenta por cobrar:'
            ])
            ->add('numeroDocumento', IntegerType::class, array('required' => false))
            ->add('soporte', TextType::class, array('required' => false))
            ->add('plazo', IntegerType::class, array('required' => true))
            ->add('fecha', DateType::class,array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('vrSaldoOriginal', IntegerType::class, array('required' => true))
            ->add('vrSubtotal', IntegerType::class, array('required' => true))
            ->add('vrTotal', IntegerType::class, array('required' => true))
            ->add('vrRetencionFuente', IntegerType::class, array('required' => true))
            ->add('guardar', SubmitType::class, ['label'=>'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CarCuentaCobrar::class,
        ]);
    }

}

