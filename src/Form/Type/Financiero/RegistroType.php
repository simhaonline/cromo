<?php

namespace App\Form\Type\Financiero;

use App\Entity\Financiero\FinCentroCosto;
use App\Entity\Financiero\FinComprobante;
use App\Entity\Financiero\FinCuenta;
use App\Entity\Financiero\FinRegistro;
use App\Entity\Financiero\FinTercero;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistroType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('cuentaRel', EntityType::class, [
                'required' => true,
                'class' => FinCuenta::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('tbl')
                        ->orderBy('tbl.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Cuenta:'
            ])
            ->add('terceroRel', EntityType::class, [
                'required' => true,
                'class' => FinTercero::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('tbl')
                        ->orderBy('tbl.nombreCorto', 'ASC');
                },
                'choice_label' => 'nombreCorto',
                'label' => 'Tercero:'
            ])
            ->add('comprobanteRel', EntityType::class, [
                'required' => true,
                'class' => FinComprobante::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('tbl')
                        ->orderBy('tbl.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Comprobante:'
            ])
            ->add('centroCostoRel', EntityType::class, [
                'required' => true,
                'class' => FinCentroCosto::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('tbl')
                        ->orderBy('tbl.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Centro costo:'
            ])
            ->add('vrDebito', NumberType::class, ['label' => 'Debito:'])
            ->add('vrCredito', NumberType::class, ['label' => 'Credito:'])
            ->add('vrBase', NumberType::class, ['label' => 'Base:'])
            ->add('descripcion', TextareaType::class, ['label' => 'Descripción:'])
            ->add('guardar', SubmitType::class, ['label' => 'Guardar', 'attr' => ['class' => 'btn btn-sm btn-primary']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FinRegistro::class,
        ]);
    }

}
