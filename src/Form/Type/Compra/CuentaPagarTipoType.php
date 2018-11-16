<?php

namespace App\Form\Type\Compra;

use App\Entity\Compra\ComCuentaPagarTipo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CuentaPagarTipoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoCuentaPagarTipoPk', TextType::class, ['label' => 'Código:'])
            ->add('nombre', TextType::class, ['label' => 'Nombre:'])
            ->add('operacion', ChoiceType::class, ['label' => 'Operación:', 'choices' => ['SUMA' => 1, 'RESTA' => -1]])
            ->add('prefijo', TextType::class, ['label' => 'Prefijo:', 'required' => false])
            ->add('codigoCuentaProveedorFk', TextType::class, ['label' => 'Cuenta proveedor:', 'required' => false])
            ->add('codigoCuentaRetencionIvaFk', TextType::class, ['label' => 'Cuenta retención IVA:', 'required' => false])
            ->add('codigoCuentaRetencionFuenteFk', TextType::class, ['label' => 'Cuenta retención fuente:', 'required' => false])
            ->add('codigoCuentaIndustriaComercioFk', TextType::class, ['label' => 'Cuenta industria y comercio:', 'required' => false])
            ->add('codigoCuentaAjustePesoFk', TextType::class, ['label' => 'Cuenta ajuste peso:', 'required' => false])
            ->add('codigoCuentaDescuentoFk', TextType::class, ['label' => 'Cuenta descuento:', 'required' => false])
            ->add('guardar', SubmitType::class, ['attr' => ['class' => 'btn btn-sm btn-primary']]);;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ComCuentaPagarTipo::class,
        ]);
    }

    public function getEstructuraPropiedadesLista()
    {
        $campos = '[
            {"campo":"codigoCuentaPagarTipoPk", "tipo":"pk",    "ayuda":"Codigo del registro",                    "titulo":"ID"},
            {"campo":"nombre",             "tipo":"texto", "ayuda":"Nombre del registro",                    "titulo":"NOMBRE"}            
        ]';
        return $campos;
    }
}
