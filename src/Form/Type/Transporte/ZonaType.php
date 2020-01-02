<?php


namespace App\Form\Type\Transporte;


use App\Entity\Transporte\TteZona;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ZonaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoZonaPk',TextType::class,['required' => true,'label' => 'Código zona:'])
            ->add('nombre',TextType::class,['required' => true,'label' => 'Nombre:'])
            ->add('guardar', SubmitType::class, ['label'=>'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TteZona::class,
        ]);
    }

    public function getEstructuraPropiedadesLista()
    {
        $campos = '[	
            {"campo":"codigoZonaPk",    "tipo":"pk",        "ayuda":"Codigo de la zona",   "titulo":"ID"},	
            {"campo":"nombre",          "tipo":"texto",     "ayuda":"zona",                "titulo":"nombre"}	
        ]';
        return $campos;
    }
    public function getEstructuraPropiedadesFiltro()
    {
        $campos = '[	
            {"child":"codigoZonaPk",    "tipo":"TextType",  "propiedades":{"label":"codigo zona:"}, "operador":"like"},	
            {"child":"nombre",          "tipo":"TextType",  "propiedades":{"label":"nombre"},       "operador":"like"}	
        ]';
        return $campos;
    }
}