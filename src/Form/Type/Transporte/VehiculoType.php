<?php

namespace App\Form\Type\Transporte;

use App\Entity\Transporte\TteVehiculo;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class VehiculoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('aseguradoraRel',EntityType::class,[
                'required' => false,
                'class' => 'App\Entity\Transporte\TteAseguradora',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('a')
                        ->orderBy('a.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Aseguradora:'
            ])
            ->add('poseedorRel',EntityType::class,[
                'required' => false,
                'class' => 'App\Entity\Transporte\TtePoseedor',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('p')
                        ->orderBy('p.nombreCorto', 'ASC');
                },
                'choice_label' => 'nombreCorto',
                'label' => 'Poseedor:'
            ])
            ->add('propietarioRel',EntityType::class,[
                'required' => false,
                'class' => 'App\Entity\Transporte\TtePoseedor',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('pr')
                        ->orderBy('pr.nombreCorto', 'ASC');
                },
                'choice_label' => 'nombreCorto',
                'label' => 'Propietario:'
            ])
            ->add('marcaRel',EntityType::class,[
                'required' => false,
                'class' => 'App\Entity\Transporte\TteMarca',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('m')
                        ->orderBy('m.codigoMarcaPk', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Marca:'
            ])
            ->add('tipoCombustibleRel',EntityType::class,[
                'required' => false,
                'class' => 'App\Entity\Transporte\TteTipoCombustible',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('tc')
                        ->orderBy('tc.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Tipo combustile:'
            ])
            ->add('tipoCarroceriaRel',EntityType::class,[
                'required' => false,
                'class' => 'App\Entity\Transporte\TteTipoCarroceria',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cr')
                        ->orderBy('cr.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Tipo carroceria:'
            ])
            ->add('colorRel',EntityType::class,[
                'required' => false,
                'class' => 'App\Entity\Transporte\TteColor',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cl')
                        ->orderBy('cl.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Color:'
            ])
            ->add('codigoVehiculoPk',TextType::class,['required' => true,'label' => 'Codigo Vehiculo:'])
            ->add('placa',TextType::class,['required' => true,'label' => 'Placa:'])
            ->add('configuracion',TextType::class,['required' => true,'label' => 'Configuracion:'])
            ->add('placaRemolque',TextType::class,['required' => true,'label' => 'Placa remolque:'])
            ->add('modelo',NumberType::class,['required' => true,'label' => 'Modelo:'])
            ->add('modeloRepotenciado',NumberType::class,['required' => false,'label' => 'Modelo repotenciado:'])
            ->add('motor',NumberType::class,['required' => true,'label' => 'Motor:'])
            ->add('numeroEjes',NumberType::class,['required' => true,'label' => 'Numero ejes:'])
            ->add('chasis',TextType::class,['required' => true,'label' => 'Chasis:'])
            ->add('serie',TextType::class,['required' => true,'label' => 'Serie:'])
            ->add('pesoVacio',NumberType::class,['required' => true,'label' => 'Peso vacio:'])
            ->add('capacidad',TextType::class,['required' => true,'label' => 'Capacidad:'])
            ->add('celular',NumberType::class,['required' => true,'label' => 'Celular:'])
            ->add('registroNacionalCarga',NumberType::class,['required' => true,'label' => 'RNDC:'])
            ->add('numeroPoliza',NumberType::class,['required' => true,'label' => 'Numero poliza:'])
            ->add('fechaVencePoliza', DateType::class,array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('fechaVenceTecnicomecanica', DateType::class,array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('comentario',TextareaType::class,['required' => false,'label' => 'Comentarios:'])
            ->add('guardar', SubmitType::class, ['label'=>'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']])
            ->add('guardarnuevo', SubmitType::class, ['label'=>'Guardar y nuevo','attr' => ['class' => 'btn btn-sm btn-primary']]);;
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TteVehiculo::class,
        ]);
    }
}
