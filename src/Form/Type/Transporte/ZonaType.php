<?php


namespace App\Form\Type\Transporte;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class ZonaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoZonaPk',TextType::class,['required' => true,'label' => 'Código zona:'])
            ->add('nombre',TextType::class,['required' => true,'label' => 'Nombre:']);


    }

    public function getEstructuraPropiedadesLista()
    {
        $campos = '[
            {"campo":"codigoZonaPk",    "tipo":"pk",        "ayuda":"Codigo de vehiculo",   "titulo":"ID"},
            {"campo":"nombre",          "tipo":"texto",     "ayuda":"placa",                "titulo":"nombre"}
        ]';
        return $campos;
    }

    public function getEstructuraPropiedadesFiltro()
    {

        $campos = '[
            {"child":"codigoZonaPk",    "tipo":"TextType",        "propiedades":{"label":"codigo zona q   "},   "operador":"like"},
            {"child":"nombre",          "tipo":"TextType",  "propiedades":{"label":"nombre"},   "operador":"like"}
        ]';

        return $campos;
    }

}