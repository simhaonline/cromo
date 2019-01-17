<?php

namespace App\Form\Type\Inventario;

use App\Entity\General\GenCiudad;
use App\Entity\Inventario\InvSucursal;
use App\Entity\Inventario\InvTercero;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SucursalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoSucursalPk', TextType::class, ['label' => 'Codigo sucursal: ', 'required' => true])
            ->add('nombre', TextType::class, ['label' => 'Nombre: ', 'required' => true])
            ->add('direccion', TextType::class, ['label' => 'Dirección: ', 'required' => true])
            ->add('contacto', TextType::class, ['label' => 'Contacto: ', 'required' => false])
            ->add('telefono', TextType::class, ['label' => 'Telefono: ', 'required' => false])
            ->add('terceroRel', EntityType::class, ['class' => InvTercero::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('it')
                        ->orderBy('it.nombreCorto','ASC');
                },
                'choice_label' => 'nombreCorto',
                'label' => 'Tercero: ',
                'attr' => ['class' => 'to-select-2']
            ])
            ->add('ciudadRel', EntityType::class, ['class' => GenCiudad::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.nombre','ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Ciudad: ',
                'attr' => ['class' => 'to-select-2']
            ])
            ->add('guardar', SubmitType::class, ['label' => 'Guardar', 'attr' => ['class' => 'btn btn-sm btn-primary']])
            ->add('guardarnuevo', SubmitType::class, ['label' => 'Guardar y nuevo', 'attr' => ['class' => 'btn btn-sm btn-primary']]);
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => InvSucursal::class,
        ]);
    }

    public function getEstructuraPropiedadesLista()
    {
        $campos = '[
            {"campo":"codigoSucursalPk",        "tipo":"pk"     ,"ayuda":"Codigo del registro",        "titulo":"ID"},
            {"campo":"nombre",                  "tipo":"texto"  ,"ayuda":"Nombre de la sucursal",      "titulo":"NOMBRE"},
            {"campo":"direccion",               "tipo":"texto"  ,"ayuda":"Direccion de la sucursal",   "titulo":"DIRECCION"},
            {"campo":"contacto",                "tipo":"texto"  ,"ayuda":"Contacto en la sucursal",    "titulo":"CONTACTO"},
            {"campo":"telefono",                "tipo":"texto"  ,"ayuda":"Telefono",                   "titulo":"TELEFONO"}                                                                     
        ]';
        return $campos;
    }

    public function getEstructuraPropiedadesExportar()
    {
        $campos = '[
            {"campo":"codigoSucursalPk",        "tipo":"pk"     ,"ayuda":"Codigo del registro",        "titulo":"ID"},
            {"campo":"nombre",                  "tipo":"texto"  ,"ayuda":"Nombre de la sucursal",      "titulo":"NOMBRE"}
            {"campo":"direccion",               "tipo":"texto"  ,"ayuda":"Direccion de la sucursal",   "titulo":"DIRECCION"}
            {"campo":"contacto",                "tipo":"texto"  ,"ayuda":"Contacto en la sucursal",    "titulo":"CONTACTO"}
            {"campo":"terceroRel.nombre",       "tipo":"texto"  ,"ayuda":"Nombre del tercero",         "titulo":"TERCERO", "relacion":""},
            {"campo":"ciudadRel.nombre",        "tipo":"texto"  ,"ayuda":"Ciudad de la sucursal",      "titulo":"CIUDAD", "relacion":""},  
            {"campo":"telefono",                "tipo":"texto"  ,"ayuda":"Telefono",                   "titulo":"TELEFONO"}                                   
        ]';
        return $campos;
    }

}
