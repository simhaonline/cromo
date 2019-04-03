<?php

namespace App\Form\Type\Transporte;

use App\Entity\Transporte\TteConsecutivo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ConsecutivoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoConsecutivoPk',TextType::class,['required' => true,'label' => 'Codigo consecutivo:'])
            ->add('intermediacion',TextType::class,['required' => true,'label' => 'Nombre:'])
            ->add('guia',NumberType::class,['required' => true,'label' => 'Consecutivo:'])
            ->add('guardar', SubmitType::class, ['label'=>'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TteConsecutivo::class,
        ]);
    }

    public function getEstructuraPropiedadesLista(){
        return '[
            {"campo":"codigoConsecutivoPk",         "tipo":"pk",        "ayuda":"Codigo del registro",     "titulo":"ID"},
            {"campo":"intermediacion",                      "tipo":"texto",     "ayuda":"Nombre del registro",     "titulo":"INTERMEDIACION"},
            {"campo":"guia",                      "tipo":"texto",     "ayuda":"Guia",     "titulo":"GUIA"}
        ]';
    }

    public function getEstructuraPropiedadesExportar(){
        return '[
            {"campo":"codigoConsecutivoPk",         "tipo":"pk",        "ayuda":"Codigo del registro",     "titulo":"ID"},
            {"campo":"intermediacion",                      "tipo":"texto",     "ayuda":"Nombre del registro",     "titulo":"INTERMEDIACION"},
            {"campo":"guia",                      "tipo":"texto",     "ayuda":"Guia",     "titulo":"GUIA"}
        ]';
    }
}
