<?php

namespace App\Form\Type\RecursoHumano;

use App\Entity\RecursoHumano\RhuConcepto;
use App\Entity\RecursoHumano\RhuConfiguracion;
use App\Entity\RecursoHumano\RhuEntidad;
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
            ->add('entidadRiesgosRel', EntityType::class, [
                'class' => RhuEntidad::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('r')
                        ->where('r.arl = 1')
                        ->orderBy('r.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'required' => true
            ])
            ->add('conceptoAuxilioTransporteRel',EntityType::class,[
                'class' => RhuConcepto::class,
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('er')
                        ->orderBy('er.nombre','ASC');
                },
                'choice_label' => function ($er) {
                    $campo = $er->getCodigoConceptoPk() . " - " . $er->getNombre();
                    return $campo;
                },
                'required' => true,
                'attr' => ['class' => 'form-control to-select-2']
            ])
            ->add('conceptoFondoSolidaridadRel',EntityType::class,[
                'class' => RhuConcepto::class,
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('er')
                        ->orderBy('er.nombre','ASC');
                },
                'choice_label' => function ($er) {
                    $campo = $er->getCodigoConceptoPk() . " - " . $er->getNombre();
                    return $campo;
                },
                'required' => true,
                'attr' => ['class' => 'form-control to-select-2']
            ])
            ->add('vrSalarioMinimo', NumberType::class, ['required' => false])
            ->add('vrAuxilioTransporte', NumberType::class, ['required' => true])
            ->add('provisionPorcentajeCesantia', NumberType::class, ['required' => false])
            ->add('provisionPorcentajeInteres', NumberType::class, ['required' => false])
            ->add('provisionPorcentajePrima', NumberType::class, ['required' => false])
            ->add('provisionPorcentajeVacacion', NumberType::class, ['required' => false]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RhuConfiguracion::class,
        ]);
    }
}
