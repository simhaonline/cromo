<?php

namespace App\Form\Type\Transporte;

use App\Entity\Transporte\TteCliente;
use App\Entity\Transporte\TteOperacion;
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
                'required' => true,
                'class' => 'App\Entity\General\GenIdentificacion',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('i')
                        ->orderBy('i.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Identificacion tipo:'
            ])
            ->add('condicionRel',EntityType::class,[
                'class' => 'App\Entity\Transporte\TteCondicion',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Condicion comercial:',
                'placeholder' => "NO SELECCIONADA",
                'required' => false,
            ])
            ->add('ciudadRel',EntityType::class,[
                'required' => false,
                'class' => 'App\Entity\General\GenCiudad',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Ciudad:'
            ])
            ->add('asesorRel',EntityType::class,[
                'required' => false,
                'class' => 'App\Entity\General\GenAsesor',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('a')
                        ->orderBy('a.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Asesor:'
            ])
            ->add('operacionRel',EntityType::class,[
                'required' => true,
                'class' => TteOperacion::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('o')
                        ->orderBy('o.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Operacion:'
            ])
            ->add('precioRel',EntityType::class,[
                'class' => 'App\Entity\Transporte\TtePrecio',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('i')
                        ->orderBy('i.nombre', 'ASC');
                },
                'choice_label' => function($er){
                    $campo = $er->getCodigoPrecioPk() .  '-'  . $er->getNombre();
                    return $campo;

                },
                'placeholder' => "NO SELECCIONADA",
                'required' => false,
            ])
            ->add('numeroIdentificacion',NumberType::class,['required' => true,'label' => 'Numero identificacion:'])
            ->add('digitoVerificacion',NumberType::class,['required' => true,'label' => 'Digito:'])
            ->add('nombreCorto',TextType::class,['required' => true,'label' => 'Nombre corto:'])
            ->add('nombreExtendido',TextType::class,['required' => true,'label' => 'Nombre extendido:'])
            ->add('nombre1',TextType::class,['required' => false,'label' => 'Primer nombre:'])
            ->add('nombre2',TextType::class,['required' => false,'label' => 'Segundo nombre:'])
            ->add('apellido1',TextType::class,['required' => false,'label' => 'Primer apellido:'])
            ->add('apellido2',TextType::class,['required' => false,'label' => 'Segundo apellido:'])
            ->add('direccion',TextType::class,['required' => true,'label' => 'Direccion:'])
            ->add('telefono',TextType::class,['required' => false,'label' => 'Telefono:'])
            ->add('movil',TextType::class,['required' => false,'label' => 'Celular:'])
            ->add('plazoPago',NumberType::class,['required' => true,'label' => 'Plazo pago:'])
            ->add('correo',TextType::class,['required' => false,'label' => 'Correo:'])
            ->add('estadoInactivo', CheckboxType::class, array('required'  => false, 'label' => 'Inactivo'))
            ->add('retencionFuenteSinBase', CheckboxType::class, array('required'  => false, 'label' => 'Retencion fuente sin base'))
            ->add('guiaPagoCredito', CheckboxType::class, array('required'  => false, 'label' => 'Pago credito'))
            ->add('guiaPagoContado', CheckboxType::class, array('required'  => false, 'label' => 'Pago contado'))
            ->add('guiaPagoDestino', CheckboxType::class, array('required'  => false, 'label' => 'Pago destino'))
            ->add('guiaPagoCortesia', CheckboxType::class, array('required'  => false, 'label' => 'Pago cortesia'))
            ->add('guiaPagoRecogida', CheckboxType::class, array('required'  => false, 'label' => 'Pago recogida'))
            ->add('porcentajeManejo',NumberType::class,['required' => true,'label' => 'Porcentaje:'])
            ->add('manejoMinimoUnidad',NumberType::class,['required' => true,'label' => 'Unidad minima:'])
            ->add('manejoMinimoDespacho',NumberType::class,['required' => true,'label' => 'Despacho minimo:'])
            ->add('precioPeso', CheckboxType::class, array('required'  => false))
            ->add('precioUnidad', CheckboxType::class, array('required'  => false))
            ->add('precioAdicional', CheckboxType::class, array('required'  => false))
            ->add('descuentoPeso',NumberType::class,['required' => true,'label' => 'Descuento peso:'])
            ->add('descuentoUnidad',NumberType::class,['required' => true,'label' => 'Descuento unidad:'])
            ->add('pesoMinimo',NumberType::class,['required' => true,'label' => 'Peso minimo:'])
            ->add('permiteRecaudo', CheckboxType::class, ['required' => false,'label' => 'Permite recaudo'])
            ->add('precioGeneral', CheckboxType::class, ['required' => false,'label' => 'Precio general'])
            ->add('redondearFlete', CheckboxType::class, ['required' => false,'label' => 'Redondear flete'])
            ->add('limitarDescuentoReexpedicion', CheckboxType::class, ['required' => false,'label' => 'Limitar descuento reexpedicion'])
            ->add('comentario',TextareaType::class,['required' => false,'label' => 'Comentarios:'])
            ->add('guardar', SubmitType::class, ['label'=>'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']])
            ->add('guardarnuevo', SubmitType::class, ['label'=>'Guardar y nuevo','attr' => ['class' => 'btn btn-sm btn-primary']]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TteCliente::class,
        ]);
    }
}
