<?php

namespace App\Form\Type\RecursoHumano;

use App\Entity\RecursoHumano\RhuDistribucion;
use App\Entity\RecursoHumano\RhuGrupo;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GrupoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoGrupoPk',TextType::class,['label' => 'Codigo grupo:', 'required' => true])
            ->add('nombre',TextType::class,['label' => 'Nombre:', 'required' => true])
            ->add('distribucionRel', EntityType::class, [
                'class' => RhuDistribucion::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('d')
                        ->orderBy('d.orden', 'ASC');
                },
                'choice_label' => 'nombre',
                'required' => false,
                'label' => 'Distribucion:'
            ])
            ->add('cargarContrato', CheckboxType::class, ['required' => false])
            ->add('cargarSoporte', CheckboxType::class, ['required' => false])
            ->add('generaPedido', CheckboxType::class, ['required' => false])
            ->add('guardar',SubmitType::class,['label' => 'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RhuGrupo::class,
        ]);
    }

}
