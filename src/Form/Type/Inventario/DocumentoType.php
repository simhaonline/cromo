<?php

namespace App\Form\Type\Inventario;

use App\Entity\Inventario\InvDocumento;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class DocumentoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre',TextType::class,['label' => 'Nombre: '])
            ->add('documentoTipoRel',EntityType::class,[
                'required' => true,
                'class' => InvSolicitudTipo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('st')
                        ->orderBy('st.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Solicitud tipo:'
            ])
            ->add('abreviatura')
            ->add('operacionInventario')
            ->add('operacionComercial')
            ->add('generaCartera')
            ->add('tipoAsientoCartera')
            ->add('generaTesoreria')
            ->add('tipoAsientoTesoreria')
            ->add('tipoValor')
            ->add('consecutivo')
            ->add('tipoCuentaIngreso')
            ->add('tipoCuentaCosto')
            ->add('tipoCuentaIva')
            ->add('codigo_cuenta_iva_fk')
            ->add('tipoCuentaRetencionFuente')
            ->add('codigoCuentaRetencionFuenteFk')
            ->add('tipoCuentaRetencionCREE')
            ->add('codigoCuentaRetencionCREEFk')
            ->add('tipoCuentaRetencionIva')
            ->add('codigoCuentaRetencionIvaFk')
            ->add('tipoCuentaTesoreria')
            ->add('codigoCuentaTesoreriaFk')
            ->add('tipoCuentaCartera')
            ->add('codigoCuentaCarteraFk')
            ->add('asignarConsecutivoCreacion')
            ->add('asignarConsecutivoImpresion')
            ->add('generaCostoPromedio')
            ->add('guardar',SubmitType::class,['label' => 'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']])
            ->add('guardarnuevo', SubmitType::class,['label' => 'Guardar y nuevo','attr' => ['class' => 'btn btn-sm btn-primary']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => InvDocumento::class,
        ]);
    }
}
