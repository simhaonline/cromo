<?php

namespace App\Form\Type\Turno;

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
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('i')
                        ->orderBy('i.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'identificacion tipo:'
            ])
            ->add('formaPagoRel', EntityType::class, [
              'class' => 'App\Entity\General\GenFormaPago',
              'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('f')
                        ->orderBy('f.nombre');
              },
              'choice_label' => 'nombre',
              'label' => 'forma pago:'
            ])
            ->add('ciudadRel', EntityType::class, [
                'class' => 'App\Entity\General\GenCiudad',
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.nombre');
                },
                'choice_label' => 'nombre',
                'label' => 'ciudad:'
            ])
            ->add('numeroIdentificacion',NumberType::class)
            ->add('digitoVerificacion',NumberType::class)
            ->add('nombreCorto',TextType::class, ['required' => true])
            ->add('nombreExtendido', TextType::class)
            ->add('nombre1', TextType::class, ['required' => true])
            ->add('nombre2', TextType::class)
            ->add('apellido1', TextType::class, ['required' => true])
            ->add('apellido2', TextType::class)
            ->add('direccion', TextType::class , ['required' => true])
            ->add('telefono', TextType::class)
            ->add('movil', TextType::class)
            ->add('plazoPago',NumberType::class)
            ->add('correo', TextType::class)
            ->add('estadoInactivo', CheckboxType::class)
            ->add('comentario',TextareaType::class,['label' => 'Comentarios:'])
            ->add('guardar', SubmitType::class, ['label'=>'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TurCliente::class,
        ]);
    }

    public function getEstructuraPropiedadesLista()
    {
        $campos = '[
            {"campo":"codigoClientePk",       "tipo":"pk"      ,"ayuda":"Codigo del registro"                      ,"titulo":"ID"},
            {"campo":"nombreCorto",           "tipo":"texto"   ,"ayuda":"Nombre del cliente"                       ,"titulo":"NOMBRE"},
            {"campo":"numeroIdentificacion",  "tipo":"texto"   ,"ayuda":"Numero de identificacion del cliente"     ,"titulo":"NUMERO_IDENTIFICACION"},
            {"campo":"telefono",              "tipo":"texto"   ,"ayuda":"Telefono del cliente"                     ,"titulo":"TELEFONO"},
            {"campo":"plazoPago",             "tipo":"texto"   ,"ayuda":"Plazo de pago del cliente"                ,"titulo":"PLAZO_PAGO"}
        ]';
        return $campos;
    }

    public function getEstructuraPropiedadesFiltro()
    {
        $campos = '[
            {"child":"codigoClientePk",     "tipo":"TextType",    "propiedades":{"label":"Codigo"}},
            {"child":"nombre", "tipo":"TextType",    "propiedades":{"label":"Nombre"}}
        ]';
        return $campos;
    }
}

