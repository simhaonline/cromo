<?php

namespace App\Form\Type\Transporte;

use App\Entity\Transporte\TteCliente;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ClienteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('identificacionRel',EntityType::class,[
                'required' => false,
                'class' => 'App\Entity\General\GenIdentificacion',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('i')
                        ->orderBy('i.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Identificacion tipo:'
            ])
            ->add('condicionRel',EntityType::class,[
                'required' => false,
                'class' => 'App\Entity\Transporte\TteCondicion',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Condicion comercial:'
            ])
            ->add('numeroIdentificacion',NumberType::class,['required' => true,'label' => 'Numero identificacion:'])
            ->add('digitoVerificacion',NumberType::class,['required' => true,'label' => 'Digito:'])
            ->add('nombreCorto',TextType::class,['required' => true,'label' => 'Razon social:'])
            ->add('nombre1',TextType::class,['required' => true,'label' => 'Primer nombre:'])
            ->add('nombre2',TextType::class,['required' => false,'label' => 'Segundo nombre:'])
            ->add('apellido1',TextType::class,['required' => false,'label' => 'Primer apellido:'])
            ->add('apellido2',TextType::class,['required' => false,'label' => 'Segundo apellido:'])
            ->add('direccion',TextType::class,['required' => true,'label' => 'Direccion:'])
            ->add('telefono',NumberType::class,['required' => true,'label' => 'Telefono:'])
            ->add('movil',TextType::class,['required' => true,'label' => 'Celular:'])
            ->add('plazoPago',NumberType::class,['required' => true,'label' => 'Plazo pago:'])
            ->add('correo',TextType::class,['required' => true,'label' => 'Correo:'])
            ->add('estadoInactivo', CheckboxType::class, array('required'  => false, 'label' => 'Inactivo'))
            ->add('comentario',TextareaType::class,['required' => false,'label' => 'Comentarios:'])
            ->add('guardar', SubmitType::class, ['label'=>'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']])
            ->add('guardarnuevo', SubmitType::class, ['label'=>'Guardar y nuevo','attr' => ['class' => 'btn btn-sm btn-primary']]);;
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TteCliente::class,
        ]);
    }
}
