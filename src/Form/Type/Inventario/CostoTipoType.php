<?php

namespace App\Form\Type\Inventario;

use App\Entity\Inventario\InvCostoTipo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CostoTipoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoCostoTipoPk',TextType::class,['label' => 'Código bodega: '])
            ->add('nombre',TextType::class,['label' => 'Nombre: '])
            ->add('consecutivo',TextType::class,['label' => 'Consecitivo: '])
            ->add('codigoComprobanteFk',TextType::class,['label' => 'Código comprobante: '])
            ->add('prefijo',TextType::class,['label' => 'Prefijo: '])
            ->add('guardar', SubmitType::class, ['label'=>'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => InvCostoTipo::class,
        ]);
    }

    public function getEstructuraPropiedadesLista()
    {
        $campos = '[
            {"campo":"codigoCostoTipoPk",          "tipo":"pk",        "ayuda":"Codigo del registro",         "titulo":"ID"},
            {"campo":"nombre",                     "tipo":"texto",     "ayuda":"Nombre del costo tipo",       "titulo":"NOMBRE"},
            {"campo":"consecutivo",                "tipo":"texto"  ,   "ayuda":"Consecutivo del registro",    "titulo":"CONSECUTIVO"},
            {"campo":"codigoComprobanteFk",        "tipo":"texto"  ,   "ayuda":"Codigo del comprobante",      "titulo":"CONSECUTIVO"},
            {"campo":"prefijo",        "tipo":"texto"  ,   "ayuda":"Prefijo del tipo",      "titulo":"PREFIJO"}    
                                     
        ]';
        return $campos;
    }

    public function getEstructuraPropiedadesFiltro()
    {
        $campos = '[            
            {"campo":"codigoCostoTipoPk",          "tipo":"pk",        "ayuda":"Codigo del registro",         "titulo":"ID"},
            {"campo":"nombre",                     "tipo":"texto",     "ayuda":"Nombre del costo tipo",       "titulo":"NOMBRE"},
            {"campo":"consecutivo",                "tipo":"texto"  ,   "ayuda":"Consecutivo del registro",    "titulo":"CONSECUTIVO"},
            {"campo":"codigoComprobanteFk",        "tipo":"texto"  ,   "ayuda":"Codigo del comprobante",      "titulo":"CONSECUTIVO"},
            {"campo":"prefijo",        "tipo":"texto"  ,   "ayuda":"Prefijo del tipo",      "titulo":"PREFIJO"}    
        ]';
        return $campos;
    }
}
