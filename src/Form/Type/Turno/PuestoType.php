<?php


namespace App\Form\Type\Turno;


use App\Entity\Financiero\FinArea;
use App\Entity\Financiero\FinCentroCosto;
use App\Entity\Turno\TurArea;
use App\Entity\Turno\TurCoordinador;
use App\Entity\Turno\TurOperacion;
use App\Entity\Turno\TurProgramador;
use App\Entity\Turno\TurProyecto;
use App\Entity\Turno\TurPuesto;
use App\Entity\Turno\TurPuestoTipo;
use App\Entity\Turno\TurSalario;
use App\Entity\Turno\TurSupervisor;
use Doctrine\ORM\EntityRepository;

use Proxies\__CG__\App\Entity\General\GenCiudad;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PuestoType  extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('programadorRel',EntityType::class,[
                'class' => TurProgramador::class,
                'query_builder' => function (EntityRepository $er) use ($options) {
                    return $er->createQueryBuilder('TurPro')
                        ->orderBy('TurPro.codigoProgramadorPk', 'ASC');
                },
                'choice_label' => 'nombre',
            ])
            ->add('ciudadRel',EntityType::class,[
                'class' => GenCiudad::class,
                'query_builder' => function (EntityRepository $er) use ($options) {
                    return $er->createQueryBuilder('GenCi')
                        ->orderBy('GenCi.codigoCiudadPk', 'ASC');
                },
                'choice_label' => 'nombre',
            ])
            ->add('centroCostoRel',EntityType::class,[
                'class' => FinCentroCosto::class,
                'query_builder' => function (EntityRepository $er) use ($options) {
                    return $er->createQueryBuilder('cc')
                        ->orderBy('cc.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
            ])
            ->add('puestoTipoRel',EntityType::class,[
                'class' => TurPuestoTipo::class,
                'query_builder' => function (EntityRepository $er) use ($options) {
                    return $er->createQueryBuilder('pt')
                        ->orderBy('pt.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
            ])
            ->add('salarioRel',EntityType::class,[
                'class' => TurSalario::class,
                'required' => false,
                'query_builder' => function (EntityRepository $er) use ($options) {
                    return $er->createQueryBuilder('s')
                        ->orderBy('s.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'placeholder' => ''
            ])
            ->add('supervisorRel',EntityType::class,[
                'class' => TurSupervisor::class,
                'required' => false,
                'query_builder' => function (EntityRepository $er) use ($options) {
                    return $er->createQueryBuilder('s')
                        ->orderBy('s.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'placeholder' => ''
            ])
            ->add('proyectoRel',EntityType::class,[
                'class' => TurProyecto::class,
                'required' => false,
                'query_builder' => function (EntityRepository $er) use ($options) {
                    return $er->createQueryBuilder('p')
                        ->orderBy('p.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'placeholder' => ''
            ])
            ->add('areaRel',EntityType::class,[
                'class' => TurArea::class,
                'required' => false,
                'query_builder' => function (EntityRepository $er) use ($options) {
                    return $er->createQueryBuilder('a')
                        ->orderBy('a.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'placeholder' => ''
            ])
            ->add('zonaRel',EntityType::class,[
                'class' => TurSupervisor::class,
                'required' => false,
                'query_builder' => function (EntityRepository $er) use ($options) {
                    return $er->createQueryBuilder('s')
                        ->orderBy('s.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'placeholder' => ''
            ])
            ->add('operacionRel',EntityType::class,[
                'class' => TurOperacion::class,
                'required' => false,
                'query_builder' => function (EntityRepository $er) use ($options) {
                    return $er->createQueryBuilder('o')
                        ->orderBy('o.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'placeholder' => ''
            ])
            ->add('coordinadorRel',EntityType::class,[
                'class' => TurCoordinador::class,
                'required' => false,
                'query_builder' => function (EntityRepository $er) use ($options) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'placeholder' => ''
            ])

            ->add('nombre', TextType::class)
            ->add('longitud', NumberType::class)
            ->add('latitud', NumberType::class)
            ->add('nombre', TextType::class)
            ->add('direccion', TextType::class, ['required' => false])
            ->add('longitud', TextType::class, ['required' => false])
            ->add('latitud', TextType::class, ['required' => false])
            ->add('telefono', TextType::class, ['required' => false])
            ->add('celular', TextType::class, ['required' => false])
            ->add('comunicacion', TextType::class, ['required' => false])
            ->add('comentario', TextareaType::class, ['required' => false])
            ->add('estadoInactivo', CheckboxType::class, ['required' => false])
            ->add('controlPuesto', CheckboxType::class, array('required' => false))
            ->add('guardar', SubmitType::class, ['label'=>'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TurPuesto::class,
        ]);
    }

}