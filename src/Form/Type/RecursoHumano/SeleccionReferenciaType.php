<?php

namespace App\Form\Type\RecursoHumano;

use App\Entity\General\GenCiudad;
use App\Entity\RecursoHumano\RhuSeleccionReferencia;
use App\Entity\RecursoHumano\RhuSeleccionReferenciaTipo;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SeleccionReferenciaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('seleccionReferenciaTipoRel', EntityType::class, array(
                'class' => RhuSeleccionReferenciaTipo::class,
                'choice_label' => 'nombre',
            ))
            ->add('ciudadRel', EntityType::class, array(
                'class' => GenCiudad::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.nombre', 'ASC');
                },
                'attr' => ['class' => 'form-control to-select-2'],
                'choice_label' => 'nombre',
                'required' => true))
            ->add('nombreCorto', TextType::class, array('required' => true))
            ->add('telefono', TextType::class, array('required' => false))
            ->add('celular', TextType::class, array('required' => false))
            ->add('direccion', TextType::class, array('required' => false))
            ->add('empresa', TextType::class, array('required' => false))
            ->add('suministraInformacion', TextType::class, array('required' => false))
            ->add('cargo', TextType::class, array('required' => false))
            ->add('tiempoLaborado', TextType::class, array('required' => false))
            ->add('motivoRetiro', TextareaType::class, array('required' => false))
            ->add('comentarios', TextareaType::class, array('required' => false))
            ->add('btnGuardar', SubmitType::class, ['label' => 'Guardar', 'attr' => ['class' => 'btn btn-sm btn-primary']]);;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RhuSeleccionReferencia::class,
        ]);
    }

}
