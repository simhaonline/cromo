<?php

namespace App\Form\Type\Transporte;

use App\Entity\Transporte\TteCiudad;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CiudadType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('zonaRel',EntityType::class,[
                'required' => false,
                'class' => 'App\Entity\Transporte\TteZona',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('z')
                        ->orderBy('z.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Zona:'
            ])
            ->add('rutaRel',EntityType::class,[
                'required' => false,
                'class' => 'App\Entity\Transporte\TteRuta',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('r')
                        ->orderBy('r.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Ruta:'
            ])
            ->add('departamentoRel',EntityType::class,[
                'required' => false,
                'class' => 'App\Entity\Transporte\TteDepartamento',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('d')
                        ->orderBy('d.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Departamento:'
            ])
            ->add('codigoCiudadPk',TextType::class,['required' => true,'label' => 'Codigo ciudad:'])
            ->add('nombre',TextType::class,['required' => true,'label' => 'Nombre:'])
            ->add('codigoDivision',TextType::class,['required' => true,'label' => 'Codigo division:'])
            ->add('nombreDivision',TextType::class,['required' => true,'label' => 'Nombre division:'])
            ->add('codigoZona',TextType::class,['required' => true,'label' => 'Codigo zona:'])
            ->add('nombreZona',TextType::class,['required' => true,'label' => 'Nombre zona:'])
            ->add('codigoMunicipio',TextType::class,['required' => true,'label' => 'Codigo municipio:'])
            ->add('nombreMunicipio',TextType::class,['required' => true,'label' => 'Nombre municipio:'])
            ->add('ordenRuta',TextType::class,['required' => true,'label' => 'Orden ruta:'])
            ->add('codigoInterface',TextType::class,['required' => true,'label' => 'Codigo interfaz:'])
            ->add('reexpedicion',CheckboxType::class,['required' => false,'label' => 'Reexpedicion'])
            ->add('lunes',CheckboxType::class,['required' => false,'label' => 'Lunes'])
            ->add('martes',CheckboxType::class,['required' => false,'label' => 'Martes'])
            ->add('miercoles',CheckboxType::class,['required' => false,'label' => 'Miercoles'])
            ->add('jueves',CheckboxType::class,['required' => false,'label' => 'Jueves'])
            ->add('viernes',CheckboxType::class,['required' => false,'label' => 'Viernes'])
            ->add('sabado',CheckboxType::class,['required' => false,'label' => 'Sabado'])
            ->add('domingo',CheckboxType::class,['required' => false,'label' => 'Domingo'])
            ->add('guardar', SubmitType::class, ['label'=>'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']])
            ->add('guardarnuevo', SubmitType::class, ['label'=>'Guardar y nuevo','attr' => ['class' => 'btn btn-sm btn-primary']]);;
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TteCiudad::class,
        ]);
    }

    public function getEstructuraPropiedadesLista(){
        return '[	
            {"campo":"codigoCiudadPk",        "tipo":"pk",        "ayuda":"Codigo del registro",     "titulo":"ID"},	
            {"campo":"nombre",                "tipo":"texto",     "ayuda":"Nombre del registro",     "titulo":"NOMBRE"},            	
            {"campo":"codigoDepartamentoFk",  "tipo":"texto",     "ayuda":"Codigo departamento",     "titulo":"COD"},	
            {"campo":"departamentoRel.nombre","tipo":"texto",     "ayuda":"Codigo departamento",     "titulo":"DEPARTAMENTO","relacion":""},	
            {"campo":"codigoRutaFk",          "tipo":"texto",     "ayuda":"Codigo ruta",             "titulo":"COD"},	
            {"campo":"rutaRel.nombre",          "tipo":"texto",     "ayuda":"Codigo ruta",             "titulo":"RUTA","relacion":""},	
            {"campo":"ordenRuta",             "tipo":"numero",    "ayuda":"Orden ruta",              "titulo":"ORD"},	
            {"campo":"codigoInterface",       "tipo":"texto",     "ayuda":"Codigo interface",        "titulo":"INTERFACE"},	
            {"campo":"reexpedicion",          "tipo":"bool",      "ayuda":"Reexpedicion",            "titulo":"REEX"},	
            {"campo":"lunes",          "tipo":"bool",      "ayuda":"Lunes",            "titulo":"LU"},	
            {"campo":"martes",          "tipo":"bool",      "ayuda":"Martes",            "titulo":"MA"},	
            {"campo":"miercoles",          "tipo":"bool",      "ayuda":"Miercoles",            "titulo":"MI"},	
            {"campo":"jueves",          "tipo":"bool",      "ayuda":"Jueves",            "titulo":"JU"},	
            {"campo":"viernes",          "tipo":"bool",      "ayuda":"Viernes",            "titulo":"VI"},	
            {"campo":"sabado",          "tipo":"bool",      "ayuda":"Sabado",            "titulo":"SA"},	
            {"campo":"domingo",          "tipo":"bool",      "ayuda":"Domingo",            "titulo":"DO"}	
        ]';
    }
    public function getEstructuraPropiedadesExportar(){
        return '[	
            {"campo":"codigoCiudadPk",      "tipo":"pk",        "ayuda":"Codigo del registro",     "titulo":"ID"},	
            {"campo":"nombre",              "tipo":"texto",     "ayuda":"Nombre del registro",     "titulo":"NOMBRE"},	
            {"campo":"codigoDivision",      "tipo":"texto",     "ayuda":"Codigo division",         "titulo":"CODIGO DIVISION"},	
            {"campo":"nombreDivision",      "tipo":"texto",     "ayuda":"Nombre division",         "titulo":"NOMBRE DIVISION"},	
            {"campo":"codigoZona",          "tipo":"texto",     "ayuda":"Codigo zona",             "titulo":"CODIGO ZONA"},	
            {"campo":"codigoMunicipio",     "tipo":"texto",     "ayuda":"Codigo municipio",        "titulo":"CODIGO MUNICIPIO"},	
            {"campo":"nombreMunicipio",     "tipo":"texto",     "ayuda":"Nombre municipio",        "titulo":"NOMBRE MUNICIPIO"},	
            {"campo":"codigoDepartamentoFk","tipo":"texto",     "ayuda":"Codigo departamento",     "titulo":"CODIGO DEPARTAMENTO"},	
            {"campo":"codigoRutaFk",        "tipo":"texto",     "ayuda":"Codigo ruta",             "titulo":"CODIGO RUTA"},	
            {"campo":"rutaRel.nombre",          "tipo":"texto",     "ayuda":"Codigo ruta",             "titulo":"RUTA","relacion":""},	
            {"campo":"ordenRuta",           "tipo":"numero",    "ayuda":"Orden ruta",              "titulo":"ORDEN RUTA"},	
            {"campo":"codigoInterface",     "tipo":"texto",     "ayuda":"Codigo interface",        "titulo":"CODIGO INTERFACE"},	
            {"campo":"reexpedicion",        "tipo":"bool",   "ayuda":"Reexpedicion",            "titulo":"REEXPEDICION"}	
        ]';
    }
    public function getOrdenamiento(){
        $campos ='[	
            {"campo":"nombre","tipo":"ASC"}	
        ]';
        return $campos;
    }
}
