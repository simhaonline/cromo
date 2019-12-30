<?php

namespace App\Form\Type\RecursoHumano;

use App\Entity\RecursoHumano\RhuCargo;
use App\Entity\RecursoHumano\RhuRequisito;
use App\Entity\RecursoHumano\RhuRequisitoCargo;
use App\Entity\RecursoHumano\RhuRequisitoConcepto;
use App\Entity\RecursoHumano\RhuRequisitoTipo;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RequisitoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('requisitoTipoRel',EntityType::class,[
                'required' => true,
                'class' => RhuRequisitoTipo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('rt')
                        ->orderBy('rt.nombre', 'ASC');
                },
                'choice_label' => 'nombre'
            ])
            ->add('cargoRel',EntityType::class,[
                'required' => true,
                'class' => RhuCargo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.nombre', 'ASC');
                },
                'choice_label' => 'nombre'
            ])
            ->add('numeroIdentificacion', TextType::class, array('required' => true))
            ->add('nombreCorto', TextType::class, array('required' => false))
            ->add('comentarios', TextareaType::class, array('required' => false))
            ->add('guardar', SubmitType::class, array('label' => 'Guardar',))
            ->getForm();
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RhuRequisito::class,
        ]);
    }

}
