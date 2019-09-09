<?php

namespace App\Form\Type\Transporte;

use App\Entity\Transporte\TtePoseedor;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PoseedorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('identificacionRel',EntityType::class,[
                'required' => true,
                'class' => 'App\Entity\General\GenIdentificacion',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('i')
                        ->orderBy('i.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Identificacion tipo:'
            ])
            ->add('ciudadRel',EntityType::class,[
                'required' => true,
                'class' => 'App\Entity\Transporte\TteCiudad',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Ciudad:'
            ])
            ->add('numeroIdentificacion',NumberType::class,['required' => true,'label' => 'Numero identificacion:'])
            ->add('correo',TextType::class,['required' => false,'label' => 'Nombre corto:'])
            ->add('nombre1',TextType::class,['required' => true,'label' => 'Primer nombre:'])
            ->add('nombre2',TextType::class,['required' => false,'label' => 'Segundo nombre:'])
            ->add('apellido1',TextType::class,['required' => false,'label' => 'Primer apellido:'])
            ->add('apellido2',TextType::class,['required' => false,'label' => 'Segundo apellido:'])
            ->add('direccion',TextType::class,['required' => true,'label' => 'Direccion:'])
            ->add('telefono',TextType::class,['required' => false,'label' => 'Telefono:'])
            ->add('movil',TextType::class,['required' => false,'label' => 'Celular:'])
            ->add('guardar', SubmitType::class, ['label'=>'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']])
            ->add('guardarnuevo', SubmitType::class, ['label'=>'Guardar y nuevo','attr' => ['class' => 'btn btn-sm btn-primary']]);;
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TtePoseedor::class,
        ]);
    }

    public function getEstructuraPropiedadesLista(){
        return '[
            {"campo":"codigoPoseedorPk",            "tipo":"pk",        "ayuda":"Codigo del registro",     "titulo":"ID"},
            {"campo":"codigoIdentificacionFk",      "tipo":"texto",     "ayuda":"Codigo identificacion",   "titulo":"TP"},
            {"campo":"numeroIdentificacion",        "tipo":"texto",     "ayuda":"Numero identificacion",   "titulo":"IDENTIFICACION"},
            {"campo":"digitoVerificacion",          "tipo":"texto",     "ayuda":"Digito verificacion",     "titulo":"DV"},
            {"campo":"nombreCorto",                 "tipo":"texto",     "ayuda":"Nombre corto",            "titulo":"NOMBRE"},
            {"campo":"nombre1",                     "tipo":"texto",     "ayuda":"Nombre1",                 "titulo":"NOMBRE1"},
            {"campo":"nombre2",                     "tipo":"texto",     "ayuda":"Nombre2",                 "titulo":"NOMBRE2"},
            {"campo":"apellido1",                   "tipo":"texto",     "ayuda":"Apellido1",               "titulo":"APELLIDO1"},
            {"campo":"apellido2",                   "tipo":"texto",     "ayuda":"Apellido2",               "titulo":"APELLIDO2"},
            {"campo":"direccion",                   "tipo":"texto",     "ayuda":"Direccion",               "titulo":"DIRECCION"},
            {"campo":"codigoCiudadFk",              "tipo":"texto",     "ayuda":"Codigo ciudad",           "titulo":"CIU"},
            {"campo":"telefono",                    "tipo":"texto",     "ayuda":"Telefono",                "titulo":"TELEFONO"},
            {"campo":"movil",                       "tipo":"texto",     "ayuda":"Movil",                   "titulo":"MOVIL"},
            {"campo":"correo",                      "tipo":"texto",     "ayuda":"Correo",                  "titulo":"CORREO"}
        ]';
    }


    public function getEstructuraPropiedadesFiltro()
    {

        $campos = '[
            {"child":"nombreCorto",               "tipo":"TextType",   "propiedades":{"label":"Nombre"},        "operador":"like"},
            {"child":"codigoPoseedorPk",      "tipo":"TextType",   "propiedades":{"label":"Identificacion"},   "operador":"like"}

        ]';

        return $campos;
    }

    public function getEstructuraPropiedadesExportar(){
        return '[
            {"{"campo":"codigoPoseedorPk",            "tipo":"pk",        "ayuda":"Codigo del registro",     "titulo":"ID"},
            {"campo":"codigoIdentificacionFk",      "tipo":"texto",     "ayuda":"Codigo identificacion",   "titulo":"CODIGO IDENTIFICACION"},
            {"campo":"numeroIdentificacion",        "tipo":"texto",     "ayuda":"Numero identificacion",   "titulo":"NUMERO IDENTIFICACION"},
            {"campo":"digitoVerificacion",          "tipo":"texto",     "ayuda":"Digito verificacion",     "titulo":"DIGITO VERIFICACION"},
            {"campo":"nombreCorto",                 "tipo":"texto",     "ayuda":"Nombre corto",            "titulo":"NOMBRE CORTO"},
            {"campo":"nombre1",                     "tipo":"texto",     "ayuda":"Nombre1",                 "titulo":"NOMBRE1"},
            {"campo":"nombre2",                     "tipo":"texto",     "ayuda":"Nombre2",                 "titulo":"NOMBRE2"},
            {"campo":"apellido1",                   "tipo":"texto",     "ayuda":"Apellido1",               "titulo":"APELLIDO1"},
            {"campo":"apellido2",                   "tipo":"texto",     "ayuda":"Apellido2",               "titulo":"APELLIDO2"},
            {"campo":"direccion",                   "tipo":"texto",     "ayuda":"Direccion",               "titulo":"DIRECCION"},
            {"campo":"codigoCiudadFk",              "tipo":"texto",     "ayuda":"Codigo ciudad",           "titulo":"CODIGO CIUDAD"},
            {"campo":"telefono",                    "tipo":"texto",     "ayuda":"Telefono",                "titulo":"TELEFONO"},
            {"campo":"movil",                       "tipo":"texto",     "ayuda":"Movil",                   "titulo":"MOVIL"},
            {"campo":"correo",                      "tipo":"texto",     "ayuda":"Correo",                  "titulo":"CORREO"}
        ]';
    }
}
