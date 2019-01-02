<?php

namespace App\Form\Type\Cartera;

use App\Entity\Cartera\CarAnticipoTipo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnticipoTipoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoAnticipoTipoPk', TextType::class, ['label'=> 'Codigo anticipo tipo pk', 'required' => true])
            ->add('nombre', TextType::class, ['label' => 'Nombre', 'required' => true])
            ->add('consecutivo', NumberType::class, ['label' => 'Consecutivo','required' => true])
            ->add('orden', IntegerType::class)
            ->add('guardar', SubmitType::class, ['label'=>'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CarAnticipoTipo::class,
        ]);
    }

    public function getEstructuraPropiedadesLista()
    {
        $campos = '[
            {"campo":"codigoAnticipoTipoPk",         "tipo":"pk"     ,"ayuda":"Codigo del registro",           "titulo":"ID"},
            {"campo":"nombre",                       "tipo":"texto"  ,"ayuda":"Nombre del tipo de anticipo",   "titulo":"NOMBRE"},
            {"campo":"consecutivo",                     "tipo":"texto"  ,"ayuda":"Consecutivo del registro",     "titulo":"CONSECUTIVO"}          
                                                                          
        ]';
        return $campos;
    }

    public function getEstructuraPropiedadesExportar()
    {
        $campos = '[
            {"campo":"codigoAnticipoTipoPk",    "tipo":"pk"     ,"ayuda":"Codigo del registro",             "titulo":"ID"},
            {"campo":"nombre",                  "tipo":"texto"  ,"ayuda":"Nombre del tipo de anticipo",     "titulo":"NOMBRE"},
            {"campo":"consecutivo",                     "tipo":"texto"  ,"ayuda":"Consecutivo del registro",     "titulo":"CONSECUTIVO"}                                         
        ]';
        return $campos;
    }
}
