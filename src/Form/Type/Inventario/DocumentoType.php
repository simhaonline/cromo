<?php

namespace App\Form\Type\Inventario;

use App\Entity\Inventario\InvDocumento;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class DocumentoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre')
            ->add('codigoDocumentoTipoFk')
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
            ->add('guardar',SubmitType::class)
            ->add('guardarnuevo', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => InvDocumento::class,
        ]);
    }
}
