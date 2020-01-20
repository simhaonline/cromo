<?php

namespace App\Form\Type\General;

use App\Entity\General\GenAsesor;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AsesorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('numeroIdentificacion',NumberType::class,['label' => 'Identificacion:', 'required' => true])
            ->add('nombre',TextType::class,['label' => 'Nombre:','required' => true])
            ->add('direccion',TextType::class,['label' => 'Direccion:','required' => true])
            ->add('telefono',TextType::class,['label' => 'Telefono:', 'required' => true])
            ->add('celular',TextType::class,['label' => 'Celular:', 'required' => true])
            ->add('email',TextType::class,['label' => 'Email:', 'required' => true])
            ->add('usuario',TextType::class,['label' => 'Usuario:', 'required' => false])
            ->add('guardar', SubmitType::class, ['label'=>'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => GenAsesor::class,
        ]);
    }

    public function getEstructuraPropiedadesLista()
    {
        $campos = '[
            {"campo":"codigoAsesorPk",                 "tipo":"pk", "ayuda":"codigo del registro",                   "titulo":"ID"},
            {"campo":"numeroIdentificacion",           "tipo":"texto", "ayuda":"numero identificacion",              "titulo":"IDENTIFICACION"},
            {"campo":"nombre",                         "tipo":"texto", "ayuda":"Nombre del asesor",                  "titulo":"NOMBRE"},
            {"campo":"direccion",                      "tipo":"texto", "ayuda":"Direccion",                          "titulo":"DIRECCION"},
            {"campo":"telefono",                       "tipo":"texto", "ayuda":"Telefono",                           "titulo":"TELEFONO"},
            {"campo":"celular",                        "tipo":"texto", "ayuda":"Celular",                            "titulo":"CELULAR"},
            {"campo":"email",                          "tipo":"texto", "ayuda":"Email",                              "titulo":"EMAIL"},
            {"campo":"usuario",                          "tipo":"texto", "ayuda":"Usuario",                              "titulo":"USUARIO"}
        ]';
        return $campos;
    }

    public function getEstructuraPropiedadesFiltro()
    {

        $campos = '[
            {"campo":"numeroIdentificacion",           "tipo":"texto", "ayuda":"numero identificacion",              "titulo":"IDENTIFICACION"},
            {"campo":"nombre",                         "tipo":"texto", "ayuda":"Nombre del asesor",                  "titulo":"NOMBRE"},
            {"campo":"direccion",                      "tipo":"texto", "ayuda":"Direccion",                          "titulo":"DIRECCION"},
            {"campo":"telefono",                       "tipo":"texto", "ayuda":"Telefono",                           "titulo":"TELEFONO"},
            {"campo":"celular",                        "tipo":"texto", "ayuda":"Celular",                            "titulo":"CELULAR"},
            {"campo":"email",                          "tipo":"texto", "ayuda":"Email",                              "titulo":"EMAIL"}
        ]';

        return $campos;
    }
}
