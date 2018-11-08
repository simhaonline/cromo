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
              'required' => true,
              'class' => 'App\Entity\General\GenFormaPago',
              'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('f')
                        ->orderBy('f.nombre');
              },
              'choice_label' => 'nombre',
              'label' => 'forma pago:'
            ])
            ->add('ciudadRel', EntityType::class, [
                'required' => true,
                'class' => 'App\Entity\General\GenCiudad',
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.nombre');
                },
                'choice_label' => 'nombre',
                'label' => 'ciudad:'
            ])
            ->add('numeroIdentificacion',NumberType::class,['required' => true])
            ->add('digitoVerificacion',NumberType::class,['required' => true])
            ->add('nombreCorto',TextType::class,['required' => true])
            ->add('nombreExtendido', TextType::class,['required' => true])
            ->add('nombre1', TextType::class,['required' => true])
            ->add('nombre2', TextType::class,['required' => true])
            ->add('apellido1', TextType::class,['required' => true])
            ->add('apellido2', TextType::class,['required' => true])
            ->add('direccion', TextType::class,['required' => true])
            ->add('telefono', TextType::class,['required' => true])
            ->add('movil', TextType::class,['required' => true])
            ->add('plazoPago',NumberType::class,['required' => true])
            ->add('correo', TextType::class,['required' => true])
            ->add('estadoInactivo', CheckboxType::class, ['required'  => false])
            ->add('comentario',TextareaType::class,['required' => false,'label' => 'Comentarios:'])
            ->add('guardar', SubmitType::class, ['label'=>'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']])
            ->add('guardarnuevo', SubmitType::class, ['label'=>'Guardar y nuevo','attr' => ['class' => 'btn btn-sm btn-primary']])
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
            {"campo":"ciudadRel.nombre",      "tipo":"texto"   ,"ayuda":"Ciudad del cliente"                       ,"titulo":"CIUDAD", "relacion":""},
            {"campo":"telefono",              "tipo":"texto"   ,"ayuda":"Telefono del cliente"                     ,"titulo":"TELEFONO"},
            {"campo":"plazoPago",             "tipo":"texto"   ,"ayuda":"Plazo de pago del cliente"                ,"titulo":"PLAZO_PAGO"},
            {"campo":"formaPagoRel.nombre",   "tipo":"texto"   ,"ayuda":"Forma de pago del cliente"                ,"titulo":"FORMA_PAGO", "relacion":""}
        ]';
        return $campos;
    }
}
