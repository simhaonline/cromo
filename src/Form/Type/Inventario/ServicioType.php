<?php

namespace App\Form\Type\Inventario;

use App\Entity\Inventario\InvServicioTipo;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ServicioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('servicioTipoRel', EntityType::class, array(
                'class' => InvServicioTipo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('st')
                        ->orderBy('st.codigoServicioTipoPk', 'ASC');
                },
                'choice_label' => 'nombre',
            ))
            ->add('comentario',TextareaType::class, array('required' => false))
            ->add('guardar', SubmitType::class,array('label'=>'Guardar'));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'App\Entity\Inventario\InvServicio'
        ]);
    }

}
