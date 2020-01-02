<?php

namespace App\Form\Type\Transporte;

use App\Entity\Transporte\TteCliente;
use App\Entity\Transporte\TteDestinatario;
use App\Entity\Transporte\TteOperacion;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class DestinatarioType extends AbstractType
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
                'required' => false,
                'class' => 'App\Entity\Transporte\TteCiudad',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Ciudad:'
            ])
            ->add('codigoDespachoClaseFk', ChoiceType::class, [
                'choices' => array(
                    'VIAJE' => 'V', 'REPARTO' => 'R',
                ),
                'required' => true,
                'label' => 'Clase:'
            ])
            ->add('numeroIdentificacion',NumberType::class,['required' => true,'label' => 'Numero identificacion:'])
            ->add('digitoVerificacion',NumberType::class,['required' => true,'label' => 'Digito:'])
            ->add('nombreCorto',TextType::class,['required' => true,'label' => 'Nombre corto:'])
            ->add('nombre1',TextType::class,['required' => false,'label' => 'Primer nombre:'])
            ->add('nombre2',TextType::class,['required' => false,'label' => 'Segundo nombre:'])
            ->add('apellido1',TextType::class,['required' => false,'label' => 'Primer apellido:'])
            ->add('apellido2',TextType::class,['required' => false,'label' => 'Segundo apellido:'])
            ->add('direccion',TextType::class,['required' => true,'label' => 'Direccion:'])
            ->add('telefono',TextType::class,['required' => true,'label' => 'Telefono:'])
            ->add('movil',TextType::class,['required' => false,'label' => 'Celular:'])
            ->add('correo',TextType::class,['required' => false,'label' => 'Correo:'])
            ->add('estadoInactivo', CheckboxType::class, array('required'  => false, 'label' => 'Inactivo'))
            ->add('comentario',TextareaType::class,['required' => false,'label' => 'Comentarios:'])
            ->add('guardar', SubmitType::class, ['label'=>'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TteDestinatario::class,
        ]);
    }

    public function getEstructuraPropiedadesLista(){
        return '[	
            {"campo":"codigoDestinatarioPk",            "tipo":"pk",        "ayuda":"Codigo del registro",     "titulo":"ID"},	
            {"campo":"nombreCorto",                      "tipo":"texto",     "ayuda":"Nombre del registro",     "titulo":"NOMBRE"},	
            {"campo":"numeroIdentificacion",            "tipo":"texto",     "ayuda":"Codigo transporte",       "titulo":"IDENTIFICACION"}	
        ]';
    }
    public function getEstructuraPropiedadesExportar(){
        return '[	
            {"campo":"codigoDestinatarioPk",            "tipo":"pk",        "ayuda":"Codigo del registro",     "titulo":"ID"},	
            {"campo":"nombre",                      "tipo":"texto",     "ayuda":"Nombre del registro",     "titulo":"NOMBRE"},	
            {"campo":"codigoTransporte",            "tipo":"texto",     "ayuda":"Codigo transporte",       "titulo":"CODIGO TRANSPORTE"},	
            {"campo":"orden",                       "tipo":"numero",    "ayuda":"Numero",                  "titulo":"NUMERO"}	
        ]';
    }
    public function getEstructuraPropiedadesFiltro()
    {
        $campos = '[	
            {"child":"codigoDestinatarioPk",                "tipo":"TextType",      "propiedades":{"label":"Codigo"}},	
            {"child":"nombreCorto",                "tipo":"TextType",      "propiedades":{"label":"Nombre"}}	
        ]';
        return $campos;
    }
}
