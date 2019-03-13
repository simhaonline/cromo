<?php

namespace App\Form\Type\Inventario;

use App\Entity\Inventario\InvContacto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('numeroIdentificacion', TextType::class, ['label' => 'Numero identificacion:','required' => true])
            ->add('nombreCorto', TextType::class, ['label' => 'Nombre', 'required' => true])
            ->add('guardar', SubmitType::class, ['label'=>'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => InvContacto::class,
        ]);
    }

    public function getEstructuraPropiedadesLista()
    {
        $campos = '[
            {"campo":"codigoContactoPk",         "tipo":"pk"     ,"ayuda":"Codigo del registro",           "titulo":"ID"},
            {"campo":"numeroIdentificacion",                     "tipo":"texto"  ,"ayuda":"Numero identificacion",     "titulo":"IDENTIFICACION"},
            {"campo":"nombreCorto",                          "tipo":"texto"  ,"ayuda":"Nombre de la importacion tipo",     "titulo":"NOMBRE"}                                                                                      
        ]';
        return $campos;
    }

    public function getEstructuraPropiedadesExportar()
    {
        $campos = '[
            {"campo":"codigoContactoPk",         "tipo":"pk"     ,"ayuda":"Codigo del registro",           "titulo":"ID"},
            {"campo":"numeroIdentificacion",                     "tipo":"texto"  ,"ayuda":"Numero identificacion",     "titulo":"IDENTIFICACION"},
            {"campo":"nombreCorto",                          "tipo":"texto"  ,"ayuda":"Nombre de la importacion tipo",     "titulo":"NOMBRE"}                                           
        ]';
        return $campos;
    }
}