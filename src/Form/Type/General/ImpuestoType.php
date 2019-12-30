<?php

namespace App\Form\Type\General;

use App\Entity\General\GenImpuesto;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImpuestoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoImpuestoPk', TextType::class, ['label' => 'Codigo impuesto: ', 'required' => true])
            ->add('nombre', TextType::class, ['label' => 'Nombre: ', 'required' => true])
            ->add('impuestoTipoRel',EntityType::class,[
                'required' => false,
                'class' => 'App\Entity\General\GenImpuestoTipo',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('it')
                        ->orderBy('it.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Impuesto tipo:'
            ])
            ->add('porcentaje', TextType::class, ['label' => 'Porcentaje: ', 'required' => true])
            ->add('base', TextType::class, ['label' => 'Base: ', 'required' => true])
            ->add('codigoCuentaFk', TextType::class, ['label' => 'Codigo cuenta fk: ', 'required' => true])
            ->add('codigoCuentaDevolucionFk', TextType::class, ['label' => 'Codigo cuenta devolucion fk: ', 'required' => true])
            ->add('guardar', SubmitType::class, ['label' => 'Guardar', 'attr' => ['class' => 'btn btn-sm btn-primary']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => GenImpuesto::class,
        ]);
    }

}
