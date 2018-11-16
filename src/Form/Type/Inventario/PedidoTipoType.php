<?php

namespace App\Form\Type\Inventario;

use App\Entity\Inventario\InvLinea;
use App\Entity\Inventario\InvPedidoTipo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PedidoTipoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoPedidoTipoPk',TextType::class,['label' => 'Código pedido tipo: '])
            ->add('nombre',TextType::class,['label' => 'Nombre: '])
            ->add('consecutivo', NumberType::class, ['label' => 'Consecutivo','required' => true])
            ->add('guardar',SubmitType::class,['label' => 'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => InvPedidoTipo::class,
        ]);
    }

    public function getEstructuraPropiedadesLista()
    {
        $campos = '[
            {"campo":"codigoPedidoTipoPk",         "tipo":"pk"     ,"ayuda":"Codigo del registro",          "titulo":"ID"},
            {"campo":"nombre",                     "tipo":"texto"  ,"ayuda":"Nombre del pedido tipo",       "titulo":"NOMBRE"},   
            {"campo":"consecutivo",                "tipo":"texto"  ,"ayuda":"Consecutivo del registro",     "titulo":"NOMBRE"}             
                                                                          
        ]';
        return $campos;
    }

    public function getEstructuraPropiedadesExportar()
    {
        $campos = '[
            {"campo":"codigoPedidoTipoPk",         "tipo":"pk"     ,"ayuda":"Codigo del registro",          "titulo":"ID"},
            {"campo":"nombre",                     "tipo":"texto"  ,"ayuda":"Nombre del pedido tipo",       "titulo":"NOMBRE"},   
            {"campo":"consecutivo",                "tipo":"texto"  ,"ayuda":"Consecutivo del registro",     "titulo":"NOMBRE"}                                      
        ]';
        return $campos;
    }
}
