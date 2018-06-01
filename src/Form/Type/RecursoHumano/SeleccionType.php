<?php

namespace App\Form\Type\RecursoHumano;

use App\Entity\RecursoHumano\RhuSeleccion;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SeleccionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('grupoPagoRel',EntityType::class,[
                'required' => false,
                'class' => 'App\Entity\RecursoHumano\RhuGrupoPago',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('gp')
                        ->orderBy('gp.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Grupo pago:'
            ])
            ->add('seleccionTipoRel',EntityType::class,[
                'required' => false,
                'class' => 'App\Entity\RecursoHumano\RhuSeleccionTipo',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('st')
                        ->orderBy('st.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Seleccion tipo:'
            ])
            ->add('solicitudRel',EntityType::class,[
                'required' => false,
                'class' => 'App\Entity\RecursoHumano\RhuSolicitud',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('slt')
                        ->orderBy('slt.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Solicitud:'
            ])
            ->add('cargoRel',EntityType::class,[
                'required' => false,
                'class' => 'App\Entity\RecursoHumano\RhuCargo',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Cargo:'
            ])
            ->add('genIdentificacionRel',EntityType::class,[
                'required' => false,
                'class' => 'App\Entity\General\GenIdentificacion',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Identificacion:'
            ])
            ->add('genCiudadRel',EntityType::class,[
                'required' => false,
                'class' => 'App\Entity\General\GenCiudad',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('gc')
                        ->orderBy('gc.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Ciudad:'
            ])
            ->add('genCiudadNacimientoRel',EntityType::class,[
                'required' => false,
                'class' => 'App\Entity\General\GenCiudad',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cn')
                        ->orderBy('cn.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Ciudad nacimiento:'
            ])
            ->add('genCiudadExpedicionRel',EntityType::class,[
                'required' => false,
                'class' => 'App\Entity\General\GenCiudad',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('ce')
                        ->orderBy('ce.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Ciudad expedicion:'
            ])
            ->add('genEstadoCivilRel',EntityType::class,[
                'required' => false,
                'class' => 'App\Entity\General\GenEstadoCivil',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('ec')
                        ->orderBy('ec.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Estado civil:'
            ])
            ->add('rhRel',EntityType::class,[
                'required' => false,
                'class' => 'App\Entity\RecursoHumano\RhuRh',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('rh')
                        ->orderBy('rh.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Rh:'
            ])
            ->add('fecha')
            ->add('numeroIdentificacion')
            ->add('nombreCorto')
            ->add('nombre1')
            ->add('nombre2')
            ->add('apellido1')
            ->add('apellido2')
            ->add('telefono')
            ->add('celular')
            ->add('direccion')
            ->add('barrio')
            ->add('correo')
            ->add('fechaNacimiento')
            ->add('comentarios')
            ->add('estadoAprobado')
            ->add('presentaPruebas')
            ->add('referenciasVerificadas')
            ->add('fechaEntrevista')
            ->add('fechaPrueba')
            ->add('estadoAutorizado')
            ->add('fechaCierre')
            ->add('guardar', SubmitType::class, ['label'=>'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']])
            ->add('guardarnuevo', SubmitType::class, ['label'=>'Guardar y nuevo','attr' => ['class' => 'btn btn-sm btn-primary']]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RhuSeleccion::class,
        ]);
    }
}
