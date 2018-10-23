<?php

namespace App\Form\Type\RecursoHumano;

use App\Entity\RecursoHumano\RhuConcepto;
use App\Entity\RecursoHumano\RhuNovedadTipo;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NovedadTipoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoNovedadTipoPk', TextType::class, ['required' => true])
            ->add('nombre', TextType::class, ['required' => false])
            ->add('afectaSalud', CheckboxType::class, ['required' => false])
            ->add('ausentismo', CheckboxType::class, ['required' => false])
            ->add('maternidad', CheckboxType::class, ['required' => false])
            ->add('paternidad', CheckboxType::class, ['required' => false])
            ->add('remunerada', CheckboxType::class, ['required' => false])
//            ->add('conceptoRel', EntityType::class, [
//                'class' => RhuConcepto::class,
//                'query_builder' => function (EntityRepository $er) {
//                    return $er->createQueryBuilder('er')
//                        ->orderBy('er.nombre');
//                }, 'choice_label' => 'nombre',
//                'required' => true
//            ])
            ->add('guardar', SubmitType::class, ['label' => 'Guardar', 'attr' => ['class' => 'btn btn-sm btn-primary']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RhuNovedadTipo::class,
        ]);
    }
}
