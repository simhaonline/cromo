<?php

namespace App\Form\Type\Transporte;

use App\Entity\Transporte\TteAseguradora;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AseguradoraType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('numeroIdentificacion',NumberType::class,['required' => true,'label' => 'Numero identificacion:'])
            ->add('digitoVerificacion',NumberType::class,['required' => true,'label' => 'Digito:'])
            ->add('nombre',TextType::class,['required' => true,'label' => 'Nombre:'])
            ->add('guardar', SubmitType::class, ['label'=>'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']])
            ->add('guardarnuevo', SubmitType::class, ['label'=>'Guardar y nuevo','attr' => ['class' => 'btn btn-sm btn-primary']]);;
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TteAseguradora::class,
        ]);
    }

    public function getEstructuraPropiedadesLista(){
        return '[
            {"campo":"codigoAseguradoraPk",         "tipo":"pk"         ,"ayuda":"Codigo del registro",             "titulo":"ID"},
            {"campo":"numeroIdentificacion",        "tipo":"texto"      ,"ayuda":"Numero de identificacion",        "titulo":"NUMERO IDENTIFICACION"},
            {"campo":"digitoVerificacion",          "tipo":"texto"      ,"ayuda":"Digito de verificacion",          "titulo":"DIGITO VERIFICACION"},
            {"campo":"nombre",                      "tipo":"texto"      ,"ayuda":"Nombre del registro",             "titulo":"NOMBRE DEL REGISTRO"}
        ]';
    }

    public function getEstructuraPropiedadesExportar(){
        return '[
            {"campo":"codigoAseguradoraPk",         "tipo":"pk"         ,"ayuda":"Codigo del registro",             "titulo":"ID"},
            {"campo":"numeroIdentificacion",        "tipo":"texto"      ,"ayuda":"Numero de identificacion",        "titulo":"NUMERO IDENTIFICACION"},
            {"campo":"digitoVerificacion",          "tipo":"texto"      ,"ayuda":"Digito de verificacion",          "titulo":"DIGITO VERIFICACION"},
            {"campo":"nombre",                      "tipo":"texto"      ,"ayuda":"Nombre del registro",             "titulo":"NOMBRE DEL REGISTRO"}
        ]';
    }
}
