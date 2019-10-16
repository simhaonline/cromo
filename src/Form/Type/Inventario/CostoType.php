<?php

namespace App\Form\Type\Inventario;

use App\Entity\General\GenMoneda;
use App\Entity\Inventario\InvCosto;
use App\Entity\Inventario\InvCostoTipo;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CostoType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('costoTipoRel',EntityType::class,[
                'required' => true,
                'class' => InvCostoTipo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('it')
                        ->orderBy('it.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Costo tipo:'
            ])
            ->add('anio',NumberType::class, ['required' => true,'label' => 'AÑO:'])
            ->add('mes',NumberType::class, ['required' => true,'label' => 'MES:'])
            ->add('comentario',TextareaType::class, ['required' => false,'label' => 'Comentario:'])
            ->add('guardar', SubmitType::class, ['label'=>'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\Inventario\InvCosto'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'App_costo';
    }

}
