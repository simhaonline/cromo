<?php

namespace App\Form\Type\RecursoHumano;

use App\Entity\RecursoHumano\RhuConcepto;
use App\Entity\RecursoHumano\RhuPension;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PensionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoPensionPk', TextType::class, ['required' => true, 'label' => 'Codigo pension:'])
            ->add('nombre', TextType::class, ['required' => true, 'label' => 'Nombre:'])
            ->add('porcentajeEmpleado', TextType::class, ['required' => true, 'label' => 'Porcentaje empleado:'])
            ->add('porcentajeEmpleador', TextType::class, ['required' => true, 'label' => 'Porcentaje empleador:'])
            ->add('orden', IntegerType::class, ['required' => true, 'label' => 'Orden:'])
            ->add('conceptoRel', EntityType::class, [
                'class' => RhuConcepto::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.nombre');
                },
                'choice_label' => 'nombre',
                'required' => true,
                'attr' => ['class'=> 'form-control to-select-2']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RhuPension::class,
        ]);
    }
}
