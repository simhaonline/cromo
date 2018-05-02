<?php

namespace App\Form\Type\Inventario;

use App\Entity\Inventario\InvSolicitudTipo;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SolicitudType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('solicitudTipoRel',EntityType::class,[
                'required' => true,
                'class' => InvSolicitudTipo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('st')
                        ->orderBy('st.nombre', 'ASC');
                },
                'choice_label' => 'nombre'
            ])
            ->add('comentarios',TextareaType::class, ['required' => false])
            ->add('soporte',TextareaType::class, ['required' => false])
            ->add('guardar', SubmitType::class, ['label'=>'Guardar'])
            ->add('guardarnuevo', SubmitType::class, ['label'=>'Guardar y nuevo']);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\Inventario\InvSolicitud'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'App_solicitud';
    }

}
