<?php

namespace App\Form\Type\Inventario;

use App\Entity\Inventario\InvSolicitudTipo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SolicitudTipoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoSolicitudTipoPk',TextType::class,['label' => 'Codigo solicitud tipo:','required' => true])
            ->add('nombre',TextType::class,['label' => 'Nombre:'])
            ->add('consecutivo',NumberType::class,['label' => 'Consecutivo:'])
            ->add('guardar', SubmitType::class,['label' => 'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']])
            ->add('guardarnuevo', SubmitType::class, ['label'=>'Guardar y nuevo','attr' => ['class' => 'btn btn-sm btn-primary']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => InvSolicitudTipo::class,
        ]);
    }

    public function getEstructuraPropiedadesLista()
    {
        $campos = '[
            {"campo":"codigoSolicitudTipoPk",       "tipo":"pk"     ,"ayuda":"Codigo del registro",        "titulo":"ID"},
            {"campo":"nombre",                      "tipo":"texto"  ,"ayuda":"Nombre de la solicitud tipo",           "titulo":"NOMBRE"},
            {"campo":"consecutivo",                 "tipo":"texto"  ,"ayuda":"Consecutivo para el tipo de solicitud",           "titulo":"NOMBRE"}                  
                                                                          
        ]';
        return $campos;
    }

    public function getEstructuraPropiedadesExportar()
    {
        $campos = '[
            {"campo":"codigoSolicitudTipoPk",       "tipo":"pk"     ,"ayuda":"Codigo del registro",        "titulo":"ID"},
            {"campo":"nombre",                      "tipo":"texto"  ,"ayuda":"Nombre de la solicitud tipo",           "titulo":"NOMBRE"},
            {"campo":"consecutivo",                 "tipo":"texto"  ,"ayuda":"Consecutivo para el tipo de solicitud",           "titulo":"NOMBRE"}                                             
        ]';
        return $campos;
    }
}
