<?php


namespace App\Form\Type\RecursoHumano;

use App\Entity\General\GenCiudad;
use App\Entity\RecursoHumano\RhuCapacitacion;
use App\Entity\RecursoHumano\RhuCapacitacionMetodologia;
use App\Entity\RecursoHumano\RhuCapacitacionTipo;
use App\Entity\RecursoHumano\RhuZona;
use App\Entity\Turno\TurPuesto;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CapacitacionType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RhuCapacitacion::class,
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ciudadRel',EntityType::class,[
                'class' => GenCiudad::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.nombre', 'ASC');
                },
                'attr' => ['class' => 'form-control to-select-2'],
                'choice_label' => 'nombre',
                'required' => true,
            ])
            ->add('capacitacionTipoRel',EntityType::class,[
                'class' => RhuCapacitacionTipo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('ct')
                        ->orderBy('ct.nombre', 'ASC');
                },
                'attr' => ['class' => 'form-control to-select-2'],
                'choice_label' => 'nombre',
                'required' => true,
            ])
            ->add('capacitacionTemaRel',EntityType::class,[
                'class' => RhuCapacitacionTipo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('ct')
                        ->orderBy('ct.nombre', 'ASC');
                },
                'attr' => ['class' => 'form-control to-select-2'],
                'choice_label' => 'nombre',
                'required' => true,
            ])
            ->add('puestoRel',EntityType::class,[
                'class' => TurPuesto::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('p')
                        ->orderBy('p.nombre', 'ASC');
                },
                'attr' => ['class' => 'form-control to-select-2'],
                'choice_label' => 'nombre',
                'required' => true,
            ])
            ->add('zonaRel',EntityType::class,[
                'class' => RhuZona::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('z')
                        ->orderBy('z.nombre', 'ASC');
                },
                'attr' => ['class' => 'form-control to-select-2'],
                'choice_label' => 'nombre',
                'required' => true,
            ])
            ->add('capacitacionMetadologiaRel',EntityType::class,[
                'class' => RhuCapacitacionMetodologia::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cm')
                        ->orderBy('cm.nombre', 'ASC');
                },
                'attr' => ['class' => 'form-control to-select-2'],
                'choice_label' => 'nombre',
                'required' => true,
            ])
            ->add('fechaCapacitacion', DateTimeType::class, array('format' => 'yyyyMMdd','required' => false))
            ->add('numeroPersonasCapacitar', IntegerType::class, array('required' => false))
            ->add('vrCapacitacion', IntegerType::class, array('required' => false))
            ->add('lugar', TextType::class, array('required' => true))
            ->add('duracion', TextType::class, array('required' => true))
            ->add('objetivo', TextareaType::class, array('required' => false))
            ->add('contenido', TextareaType::class, array('required' => false))
            ->add('facilitador', TextType::class, array('required' => true))
            ->add('numeroIdentificacionFacilitador', TextType::class, array('required' => false))
            ->add('guardar', SubmitType::class, ['label'=>'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']]);

    }
}