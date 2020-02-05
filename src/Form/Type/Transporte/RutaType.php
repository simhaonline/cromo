<?php

namespace App\Form\Type\Transporte;

use App\Entity\Transporte\TteOperacion;
use App\Entity\Transporte\TteRuta;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RutaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoRutaPk', TextType::class, ['required' => true, 'label' => 'Codigo ruta:'])
            ->add('codigoDespachoClaseFk', ChoiceType::class, [
                'choices' => array(
                    'VIAJE' => 'V', 'REPARTO' => 'R',
                ),
                'required' => true,
                'label' => 'Clase:'
            ])
            ->add('operacionRel', EntityType::class, array(
                'class' => TteOperacion::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('o')
                        ->orderBy('o.nombre', 'ASC');
                },
                'label' => 'Operacion:',
                'choice_label' => 'nombre',
            ))
            ->add('nombre', TextType::class, ['required' => true, 'label' => 'Nombre:'])
            ->add('btnGuardar', SubmitType::class, ['label' => 'Guardar', 'attr' => ['class' => 'btn btn-sm btn-primary']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TteRuta::class,
        ]);
    }

}
