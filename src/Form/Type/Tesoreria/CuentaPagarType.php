<?php

namespace App\Form\Type\Tesoreria;

use App\Entity\General\GenBanco;
use App\Entity\Tesoreria\TesCuentaPagar;
use App\Entity\Tesoreria\TesCuentaPagarTipo;
use App\Entity\Tesoreria\TesTercero;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CuentaPagarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('comentario', TextareaType::class, array('required' => false))
            ->add('numeroDocumento', IntegerType::class, array('required' => false))
            ->add('fecha', DateType::class,array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('plazo', IntegerType::class, array('required' => true))
            ->add('vrSaldoOriginal', IntegerType::class, array('required' => true))
            ->add('vrSubtotal', IntegerType::class, array('required' => true))
            ->add('vrTotal', IntegerType::class, array('required' => true))
            ->add('vrRetencionFuente', IntegerType::class, array('required' => true))

            ->add('cuentaPagarTipoRel', EntityType::class, [
                'class' => TesCuentaPagarTipo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cp')
                        ->where('cp.saldoInicial = 1')
                        ->orderBy('cp.codigoCuentaPagarTipoPk', 'ASC');
                },
                'required' => false,
                'choice_label' => 'nombre',
                'placeholder' => 'TODOS'
            ])
            ->add('bancoRel', EntityType::class, [
                'class' => GenBanco::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cp')
                        ->orderBy('cp.codigoBancoPk', 'ASC');
                },
                'required' => false,
                'choice_label' => 'nombre',
                'placeholder' => 'TODOS'
            ])
            ->add('guardar', SubmitType::class, ['label'=>'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TesCuentaPagar::class,
        ]);
    }
}
