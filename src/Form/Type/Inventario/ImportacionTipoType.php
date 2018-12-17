<?php

namespace App\Form\Type\Inventario;

use App\Entity\Inventario\InvImportacionTipo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImportacionTipoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoImportacionTipoPk', TextType::class, ['label'=> 'Codigo importacion tipo pk', 'required' => true])
            ->add('codigoComprobanteFk', TextType::class, ['label' => 'comprobante','required' => false])
            ->add('nombre', TextType::class, ['label' => 'Nombre', 'required' => true])
            ->add('consecutivo', NumberType::class, ['label' => 'Consecutivo','required' => true])
            ->add('guardar', SubmitType::class, ['label'=>'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']])
            ->add('guardarnuevo', SubmitType::class, ['label'=>'Guardar y nuevo','attr' => ['class' => 'btn btn-sm btn-primary']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => InvImportacionTipo::class,
        ]);
    }

    public function getEstructuraPropiedadesLista()
    {
        $campos = '[
            {"campo":"codigoImportacionTipoPk",         "tipo":"pk"     ,"ayuda":"Codigo del registro",           "titulo":"ID"},
            {"campo":"nombre",                          "tipo":"texto"  ,"ayuda":"Nombre de la importacion tipo",     "titulo":"NOMBRE"},
            {"campo":"consecutivo",                     "tipo":"texto"  ,"ayuda":"Consecutivo del registro",     "titulo":"NOMBRE"}                
                                                                          
        ]';
        return $campos;
    }

    public function getEstructuraPropiedadesExportar()
    {
        $campos = '[
            {"campo":"codigoImportacionTipoPk",         "tipo":"pk"     ,"ayuda":"Codigo del registro",           "titulo":"ID"},
            {"campo":"nombre",                "tipo":"texto"  ,"ayuda":"Nombre de la importacion tipo",     "titulo":"NOMBRE"},
            {"campo":"consecutivo",                "tipo":"texto"  ,"ayuda":"Consecutivo del registro",     "titulo":"NOMBRE"}                                           
        ]';
        return $campos;
    }
}
