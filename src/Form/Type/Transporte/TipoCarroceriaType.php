<?php

namespace App\Form\Type\Transporte;

use App\Entity\Transporte\TteTipoCarroceria;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class TipoCarroceriaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoTipoCarroceriaPk',TextType::class,['required' => true,'label' => 'Codigo tipo carroceria:'])
            ->add('nombre',TextType::class,['required' => true,'label' => 'Nombre:'])
            ->add('guardar', SubmitType::class, ['label'=>'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']])
            ->add('guardarnuevo', SubmitType::class, ['label'=>'Guardar y nuevo','attr' => ['class' => 'btn btn-sm btn-primary']]);;
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TteTipoCarroceria::class,
        ]);
    }

    public function getEstructuraPropiedadesLista(){
        return '[	
            {"campo":"codigoTipoCarroceriaPk",         "tipo":"pk",        "ayuda":"Codigo del registro",     "titulo":"ID"},	
            {"campo":"nombre",                         "tipo":"texto",     "ayuda":"Nombre del registro",     "titulo":"NOMBRE"}	
        ]';
    }
    public function getEstructuraPropiedadesExportar(){
        return '[	
            {"campo":"codigoTipoCarroceriaPk",         "tipo":"pk",        "ayuda":"Codigo del registro",     "titulo":"ID"},	
            {"campo":"nombre",                         "tipo":"texto",     "ayuda":"Nombre del registro",     "titulo":"NOMBRE"}	
        ]';
    }
}
