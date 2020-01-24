<?php

namespace App\Form\Type\Financiero;

use App\Entity\Financiero\FinComprobante;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ComprobanteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoComprobantePk', TextType::class, ['label' => 'Codigo comprobante:'])
            ->add('nombre', TextType::class, ['label' => 'Nombre:'])
            ->add('consecutivo', NumberType::class, ['label' => 'Consecutivo:'])
            ->add('guardar', SubmitType::class, ['label' => 'Guardar', 'attr' => ['class' => 'btn btn-sm btn-primary']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FinComprobante::class,
        ]);
    }

    public function getEstructuraPropiedadesLista()
    {
        $campos = '[
            {"campo":"codigoComprobantePk",     "tipo":"pk"     ,"ayuda":"Codigo del registro",          "titulo":"ID"},
            {"campo":"nombre",                  "tipo":"texto"  ,"ayuda":"Nombre del comprobante",       "titulo":"NOMBRE"},
            {"campo":"consecutivo",                  "tipo":"texto"  ,"ayuda":"Consecutivo",       "titulo":"CONSECUTIVO"}          
                                                                          
        ]';
        return $campos;
    }

    public function getEstructuraPropiedadesExportar()
    {
        $campos = '[
            {"campo":"codigoComprobantePk",     "tipo":"pk"     ,"ayuda":"Codigo del registro",          "titulo":"ID"},
            {"campo":"nombre",                  "tipo":"texto"  ,"ayuda":"Nombre del comprobante",       "titulo":"NOMBRE"}                               
        ]';
        return $campos;
    }
}
