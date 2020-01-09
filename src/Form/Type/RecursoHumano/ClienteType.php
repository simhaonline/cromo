<?php

namespace App\Form\Type\RecursoHumano;

use App\Entity\General\GenAsesor;
use App\Entity\General\GenCobertura;
use App\Entity\General\GenDimension;
use App\Entity\General\GenOrigenCapital;
use App\Entity\General\GenRegimen;
use App\Entity\General\GenSectorComercial;
use App\Entity\General\GenSectorEconomico;
use App\Entity\General\GenTipoPersona;
use App\Entity\RecursoHumano\RhuCliente;
use App\Entity\Turno\TurCliente;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClienteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('identificacionRel', EntityType::class, [
                'required' => true,
                'class' => 'App\Entity\General\GenIdentificacion',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('i')
                        ->orderBy('i.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'identificacion tipo:'
            ])
            ->add('formaPagoRel', EntityType::class, [
                'class' => 'App\Entity\General\GenFormaPago',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('f')
                        ->orderBy('f.nombre');
                },
                'choice_label' => 'nombre',
                'label' => 'forma pago:'
            ])
            ->add('ciudadRel', EntityType::class, [
                'class' => 'App\Entity\General\GenCiudad',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.nombre');
                },
                'choice_label' => 'nombre',
                'label' => 'ciudad:'
            ])
            ->add('asesorRel', EntityType::class, [
                'class' => GenAsesor::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('a')
                        ->orderBy('a.nombre');
                },
                'choice_label' => 'nombre',
                'label' => 'ciudad:'
            ])
            ->add('numeroIdentificacion', NumberType::class)
            ->add('digitoVerificacion', NumberType::class)
            ->add('nombreCorto', TextType::class, ['required' => true])
            ->add('nombreExtendido', TextType::class)
            ->add('nombre1', TextType::class, ['required' => false])
            ->add('nombre2', TextType::class, ['required' => false])
            ->add('apellido1', TextType::class, ['required' => false])
            ->add('apellido2', TextType::class, ['required' => false])
            ->add('direccion', TextType::class, ['required' => false])
            ->add('telefono', TextType::class, ['required' => false])
            ->add('movil', TextType::class, ['required' => false])
            ->add('plazoPago', NumberType::class)
            ->add('correo', TextType::class, ['required' => false])
            ->add('estadoInactivo', CheckboxType::class, ['required' => false])
            ->add('comentario', TextareaType::class, ['required' => false])
            ->add('guardar', SubmitType::class, ['label' => 'Guardar', 'attr' => ['class' => 'btn btn-sm btn-primary']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RhuCliente::class,
        ]);
    }

}

