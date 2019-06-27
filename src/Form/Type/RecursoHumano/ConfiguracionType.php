<?php

namespace App\Form\Type\RecursoHumano;

use App\Entity\RecursoHumano\RhuConcepto;
use App\Entity\RecursoHumano\RhuConfiguracion;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConfiguracionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('conceptoAuxilioTransporteRel',EntityType::class,[
                'class' => RhuConcepto::class,
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('er')
                        ->orderBy('er.nombre','ASC');
                },'choice_label' => 'nombre',
                'required' => true,
                'attr' => ['class' => 'form-control to-select-2']
            ])
            ->add('conceptoFondoSolidaridadRel',EntityType::class,[
                'class' => RhuConcepto::class,
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('er')
                        ->orderBy('er.nombre','ASC');
                },'choice_label' => 'nombre',
                'required' => true,
                'attr' => ['class' => 'form-control to-select-2']
            ])
            ->add('vrSalarioMinimo', NumberType::class, ['required' => false])
            ->add('vrAuxilioTransporte', NumberType::class, ['required' => true]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RhuConfiguracion::class,
        ]);
    }
}
