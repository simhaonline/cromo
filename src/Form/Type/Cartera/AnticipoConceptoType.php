<?php

namespace App\Form\Type\Cartera;

use App\Entity\Cartera\CarAnticipoConcepto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnticipoConceptoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoAnticipoConceptoPk', TextType::class, ['label'=> 'Codigo concepto pk', 'required' => true])
            ->add('nombre', TextType::class, ['label' => 'Nombre', 'required' => true])
            ->add('guardar', SubmitType::class, ['label'=>'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CarAnticipoConcepto::class,
        ]);
    }

    public function getEstructuraPropiedadesLista()
    {
        $campos = '[
            {"campo":"codigoAnticipoConceptoPk",         "tipo":"pk"     ,"ayuda":"Codigo del registro",           "titulo":"ID"},
            {"campo":"nombre",                          "tipo":"texto"  ,"ayuda":"Nombre del concepto de anticipo",     "titulo":"NOMBRE"}             
                                                                          
        ]';
        return $campos;
    }

    public function getEstructuraPropiedadesExportar()
    {
        $campos = '[
            {"campo":"codigoAnticipoConceptoPk",    "tipo":"pk"     ,"ayuda":"Codigo del registro",             "titulo":"ID"},
            {"campo":"nombre",                      "tipo":"texto"  ,"ayuda":"Nombre del concepto de anticipo", "titulo":"NOMBRE"}                                         
        ]';
        return $campos;
    }
}
