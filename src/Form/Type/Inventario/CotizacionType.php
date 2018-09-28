<?php

namespace App\Form\Type\Inventario;

use App\Entity\Inventario\InvCotizacion;
use App\Entity\Inventario\InvCotizacionTipo;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CotizacionType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('cotizacionTipoRel',EntityType::class,[
                'required' => true,
                'class' => InvCotizacionTipo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('st')
                        ->orderBy('st.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Solicitud tipo:'
            ])
            ->add('comentarios',TextareaType::class, ['required' => false,'label' => 'Comentarios:'])
            ->add('soporte',TextType::class, ['required' => false,'label' => 'Soporte:'])
            ->add('guardar', SubmitType::class, ['label'=>'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => InvCotizacion::class
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'App_solicitud';
    }

}
