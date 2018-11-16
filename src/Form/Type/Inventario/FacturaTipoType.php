<?php

namespace App\Form\Type\Inventario;

use App\Entity\Inventario\InvFacturaTipo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class FacturaTipoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoFacturaTipoPk',TextType::class,['label' => 'Codigo factura tipo:','required' => true])
            ->add('nombre',TextType::class,['label' => 'Nombre:','required' => true])
            ->add('guardar', SubmitType::class, ['label'=>'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => InvFacturaTipo::class,
        ]);
    }

    public function getEstructuraPropiedadesLista()
    {
        $campos = '[
            {"campo":"codigoFacturaTipoPk",         "tipo":"pk"     ,"ayuda":"Codigo del registro",           "titulo":"ID"},
            {"campo":"nombre",                      "tipo":"texto"  ,"ayuda":"Nombre de la factura tipo",     "titulo":"NOMBRE"}                
                                                                          
        ]';
        return $campos;
    }

    public function getEstructuraPropiedadesExportar()
    {
        $campos = '[
            {"campo":"codigoFacturaTipoPk",         "tipo":"pk"     ,"ayuda":"Codigo del registro",           "titulo":"ID"},
            {"campo":"nombre",                      "tipo":"texto"  ,"ayuda":"Nombre de la factura tipo",     "titulo":"NOMBRE"}                                            
        ]';
        return $campos;
    }
}
