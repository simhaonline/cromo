<?php

namespace App\Form\Type\Inventario;

use App\Entity\Inventario\InvImportacionCosto;
use App\Entity\Inventario\InvImportacionCostoConcepto;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImportacionCostoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('vrValor', TextType::class, ['required' => true])
            ->add('importacionCostoConceptoRel',EntityType::class,[
                'class' => InvImportacionCostoConcepto::class,
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('er')
                        ->orderBy('er.nombre','DESC');
                }, 'required' => true,
                'choice_label' => 'nombre'
            ])
            ->add('guardar', SubmitType::class, ['label' => 'Guardar', 'attr' => ['class' => 'btn btn-sm btn-primary']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => InvImportacionCosto::class,
        ]);
    }
}
