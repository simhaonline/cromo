<?php

namespace App\Form\Type\Inventario;

use App\Entity\Inventario\InvMarca;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MarcaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoMarcaPk',TextType::class,['label' => 'Código marca: '])
            ->add('nombre',TextType::class,['label' => 'Nombre: ','required' => false])
            ->add('guardar',SubmitType::class,['label' => 'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => InvMarca::class,
        ]);
    }

    public function getEstructuraPropiedadesLista()
    {
        $campos = '[
            {"campo":"codigoMarcaPk",         "tipo":"pk"     ,"ayuda":"Codigo del registro",           "titulo":"ID"},
            {"campo":"nombre",                "tipo":"texto"  ,"ayuda":"Nombre de la factura tipo",     "titulo":"NOMBRE"}                
                                                                          
        ]';
        return $campos;
    }

    public function getEstructuraPropiedadesExportar()
    {
        $campos = '[
            {"campo":"codigoMarcaPk",         "tipo":"pk"     ,"ayuda":"Codigo del registro",           "titulo":"ID"},
            {"campo":"nombre",                "tipo":"texto"  ,"ayuda":"Nombre de la factura tipo",     "titulo":"NOMBRE"}                                         
        ]';
        return $campos;
    }
}
