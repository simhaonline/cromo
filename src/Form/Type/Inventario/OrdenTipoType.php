<?php

namespace App\Form\Type\Inventario;

use App\Entity\Inventario\InvOrdenTipo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrdenTipoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoOrdenTipoPk',TextType::class,['label' => 'Codigo orden compra tipo:','required' => true])
            ->add('nombre',TextType::class,['label' => 'Nombre:','required' => true])
            ->add('consecutivo',NumberType::class,['label' => 'Consecutivo:'])
            ->add('guardar',SubmitType::class,['label' => 'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']])
            ->add('guardarnuevo',SubmitType::class,['label' => 'Guardar y nuevo','attr' => ['class' => 'btn btn-sm btn-primary']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => InvOrdenTipo::class,
        ]);
    }

    public function getEstructuraPropiedadesLista()
    {
        $campos = '[
            {"campo":"codigoOrdenTipoPk",          "tipo":"pk"     ,"ayuda":"Codigo del registro",          "titulo":"ID"},
            {"campo":"nombre",                     "tipo":"texto"  ,"ayuda":"Nombre de las ordenes tipo",       "titulo":"NOMBRE"},   
            {"campo":"consecutivo",                "tipo":"texto"  ,"ayuda":"Consecutivo del registro",     "titulo":"NOMBRE"}             
                                                                          
        ]';
        return $campos;
    }

    public function getEstructuraPropiedadesExportar()
    {
        $campos = '[
            {"campo":"codigoOrdenTipoPk",          "tipo":"pk"     ,"ayuda":"Codigo del registro",          "titulo":"ID"},
            {"campo":"nombre",                     "tipo":"texto"  ,"ayuda":"Nombre de las ordenes tipo",       "titulo":"NOMBRE"},   
            {"campo":"consecutivo",                "tipo":"texto"  ,"ayuda":"Consecutivo del registro",     "titulo":"NOMBRE"}                                      
        ]';
        return $campos;
    }
}
