<?php

namespace App\Form\Type\RecursoHumano;

use App\Entity\RecursoHumano\RhuConcepto;
use App\Entity\RecursoHumano\RhuNovedadTipo;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NovedadTipoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoNovedadTipoPk', TextType::class, ['required' => true, 'label' => 'Codigo novedad tipo:'])
            ->add('nombre', TextType::class, ['required' => false,'label' => 'Nombre:'])
            ->add('subTipo', ChoiceType::class,[
                'choices' => [
                    'LICENCIA' => 'L','INCAPACIDAD' => 'I'
                ],
                'required' => true,
                'label' => 'Subtipo:'])
            ->add('conceptoRel', EntityType::class, [
                'class' => RhuConcepto::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('er')
                        ->orderBy('er.nombre');
                }, 'choice_label' => 'nombre',
                'required' => true,
                'label' => 'Concepto:'
            ])
            ->add('abreviatura', TextType::class, ['required' => false,'label' => 'Abreviatura:'])
            ->add('guardar', SubmitType::class, ['label' => 'Guardar', 'attr' => ['class' => 'btn btn-sm btn-primary']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RhuNovedadTipo::class,
        ]);
    }

}
