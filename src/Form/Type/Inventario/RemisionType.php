<?php

namespace App\Form\Type\Inventario;

use App\Entity\Inventario\InvRemision;
use App\Entity\Inventario\InvRemisionTipo;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RemisionType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('remisionTipoRel',EntityType::class,[
                'required' => true,
                'class' => InvRemisionTipo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('rt')
                        ->orderBy('rt.orden', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Remision tipo:'
            ])
            ->add('asesorRel',EntityType::class,[
                'required' => true,
                'class' => 'App\Entity\General\GenAsesor',
                'query_builder' => function (EntityRepository $er) use ($options) {
                    return $er->createQueryBuilder('a')
                        ->orderBy('a.nombre', 'ASC');
                },
                'choice_label' => 'nombre'
            ])
            ->add('soporte',TextType::class, ['required' => false,'label' => 'Soporte:'])
            ->add('comentario',TextareaType::class, ['required' => false,'label' => 'Comentario:'])
            ->add('guardar', SubmitType::class, ['label'=>'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\Inventario\InvRemision'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'App_remision';
    }
}
