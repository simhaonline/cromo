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
            ->add('tipoPersonaRel',EntityType::class,[
                'required' => true,
                'class' => 'App\Entity\General\GenTipoPersona',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('tp')
                        ->orderBy('tp.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Tipo persona:'
            ])
            ->add('regimenRel',EntityType::class,[
                'required' => true,
                'class' => 'App\Entity\General\GenRegimen',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('r')
                        ->orderBy('r.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Regimen:'
            ])
            ->add('responsabilidadFiscalRel',EntityType::class,[
                'required' => true,
                'class' => 'App\Entity\General\GenResponsabilidadFiscal',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('rf')
                        ->orderBy('rf.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Responsabilidad fiscal:'
            ])
            ->add('condicionRel',EntityType::class,[
                'class' => 'App\Entity\Transporte\TteCondicion',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Condicion comercial:',
                'required' => true,
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
            ->add('numeroIdentificacion',NumberType::class,['required' => true,'label' => 'Numero identificacion:'])
            ->add('digitoVerificacion',NumberType::class,['required' => true,'label' => 'Digito:'])
            ->add('nombreCorto',TextType::class,['required' => true,'label' => 'Nombre corto:'])
            ->add('nombreExtendido',TextType::class,['required' => true,'label' => 'Nombre extendido:'])
            ->add('nombre1',TextType::class,['required' => false,'label' => 'Primer nombre:'])
            ->add('nombre2',TextType::class,['required' => false,'label' => 'Segundo nombre:'])
            ->add('apellido1',TextType::class,['required' => false,'label' => 'Primer apellido:'])
            ->add('apellido2',TextType::class,['required' => false,'label' => 'Segundo apellido:'])
            ->add('direccion',TextType::class,['required' => true,'label' => 'Direccion:'])
            ->add('barrio',TextType::class,['required' => true,'label' => 'Barrio:'])
            ->add('codigoPostal',TextType::class,['required' => true,'label' => 'Codigo postal:'])
            ->add('telefono',TextType::class,['required' => false,'label' => 'Telefono:'])
            ->add('movil',TextType::class,['required' => false,'label' => 'Celular:'])
            ->add('plazoPago',NumberType::class,['required' => true,'label' => 'Plazo pago:'])
            ->add('correo',TextType::class,['required' => false,'label' => 'Correo:'])
            ->add('codigoCIUU',TextType::class,['required' => false,'label' => 'CIUU:'])
            ->add('estadoInactivo', CheckboxType::class, array('required'  => false, 'label' => 'Inactivo'))
            ->add('retencionFuenteSinBase', CheckboxType::class, array('required'  => false, 'label' => 'Retencion fuente sin base'))
            ->add('facturaAgrupadaDestino', CheckboxType::class, array('required'  => false, 'label' => 'Factura agrupada destino'))
            ->add('guiaPagoCredito', CheckboxType::class, array('required'  => false, 'label' => 'Pago credito'))
            ->add('guiaPagoContado', CheckboxType::class, array('required'  => false, 'label' => 'Pago contado'))
            ->add('guiaPagoDestino', CheckboxType::class, array('required'  => false, 'label' => 'Pago destino'))
            ->add('guiaPagoCortesia', CheckboxType::class, array('required'  => false, 'label' => 'Pago cortesia'))
            ->add('guiaPagoRecogida', CheckboxType::class, array('required'  => false, 'label' => 'Pago recogida'))
            ->add('requiereEstadoSoporteFactura', CheckboxType::class, array('required'  => false, 'label' => 'Requiere estado soporte factura'))
            ->add('comentario',TextareaType::class,['required' => false,'label' => 'Comentarios:'])
            ->add('ordenarImpresionAlfabeticamenteDestino', CheckboxType::class, array('required'  => false, 'label' => 'Ordenar impresion alfabeticamente por destino'))
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
