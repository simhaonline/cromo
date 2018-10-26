<?php

namespace App\Form\Type\Compra;

use App\Entity\Compra\ComConcepto;
use App\Entity\Compra\ComConceptoTipo;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConceptoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoConceptoPk', TextType::class, ['label' => 'Código:'])
            ->add('nombre', TextType::class, ['label' => 'Nombre:'])
            ->add('porIva', IntegerType::class, ['label' => 'Porcentaje de IVA'])
            ->add('conceptoTipoRel', EntityType::class, [
                'class' => ComConceptoTipo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('ct')
                        ->orderBy('ct.nombre');
                },
                'choice_label' => 'nombre',
                'label' => 'Concepto Tipo:',

            ])
            ->add('guardar', SubmitType::class, ['attr' => ['class' => 'btn btn-sm btn-primary']]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ComConcepto::class,
        ]);
    }
}
