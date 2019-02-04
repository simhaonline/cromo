<?php

namespace App\Form\Type\Financiero;

use App\Entity\Financiero\FinCentroCosto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CentroCostoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoCentroCostoPk', TextType::class, ['label' => 'Codigo centro costo:'])
            ->add('nombre', TextType::class, ['label' => 'Nombre:'])
            ->add('estadoInactivo', CheckboxType::class, ['label' => 'Estado activo', 'required' => false])
            ->add('guardar', SubmitType::class, ['label' => 'Guardar', 'attr' => ['class' => 'btn btn-sm btn-primary']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FinCentroCosto::class,
        ]);
    }

    public function getEstructuraPropiedadesLista()
    {
        $campos = '[
            {"campo":"codigoCentroCostoPk",   "tipo":"pk",        "ayuda":"Codigo del asiento",        "titulo":"ID"},
            {"campo":"nombre",                "tipo":"texto",    "ayuda":"Nombre", "titulo":"NOMBRE"},
            {"campo":"estadoInactivo",      "tipo":"bool",      "ayuda":"",                          "titulo":"AUT"}                             
        ]';
        return $campos;
    }

    public function getEstructuraPropiedadesFiltro()
    {

        $campos = '[
            {"campo":"codigoCentroCostoPk",   "tipo":"pk",        "ayuda":"Codigo del asiento",        "titulo":"ID"},
            {"campo":"nombre",                "tipo":"texto",    "ayuda":"Nombre", "titulo":"NOMBRE"},
            {"campo":"estadoInactivo",      "tipo":"bool",      "ayuda":"",                          "titulo":"AUT"}    
   
        ]';
        return $campos;
    }
}
