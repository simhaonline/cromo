<?php

namespace App\Form\Type\General;

use App\Entity\General\GenImpuesto;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImpuestoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoImpuestoPk', TextType::class, ['label' => 'Codigo impuesto: ', 'required' => true])
            ->add('nombre', TextType::class, ['label' => 'Nombre: ', 'required' => true])
            ->add('impuestoTipoRel',EntityType::class,[
                'required' => false,
                'class' => 'App\Entity\General\GenImpuestoTipo',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('it')
                        ->orderBy('it.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Impuesto tipo:'
            ])
            ->add('porcentaje', TextType::class, ['label' => 'Porcentaje: ', 'required' => true])
            ->add('base', TextType::class, ['label' => 'Base: ', 'required' => true])
            ->add('codigoCuentaFk', TextType::class, ['label' => 'Codigo cuenta fk: ', 'required' => true])
            ->add('codigoCuentaDevolucionFk', TextType::class, ['label' => 'Codigo cuenta devolucion fk: ', 'required' => true])
            ->add('guardar', SubmitType::class, ['label' => 'Guardar', 'attr' => ['class' => 'btn btn-sm btn-primary']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => GenImpuesto::class,
        ]);
    }

    public function getEstructuraPropiedadesLista()
    {
        $campos = '[
            {"campo":"codigoImpuestoPk",                 "tipo":"pk", "ayuda":"codigo del registro",                   "titulo":"ID"},
            {"campo":"nombre",                         "tipo":"texto", "ayuda":"Nombre del impuesto",                  "titulo":"NOMBRE"},
            {"campo":"porcentaje",                      "tipo":"texto", "ayuda":"Direccion",                          "titulo":"DIRECCION"},
            {"campo":"base",                       "tipo":"texto", "ayuda":"Telefono",                           "titulo":"TELEFONO"},
            {"campo":"impuestoTipoRel.nombre",       "tipo":"texto"  ,"ayuda":"Tipo de impuesto",         "titulo":"IMPUESTO_TIPO", "relacion":""},
            {"campo":"codigoCuentaFk",                        "tipo":"texto", "ayuda":"Celular",                            "titulo":"CELULAR"},
            {"campo":"codigoCuentaDevolucionFk",                          "tipo":"texto", "ayuda":"Email",                              "titulo":"EMAIL"}
        ]';
        return $campos;
    }

    public function getEstructuraPropiedadesFiltro()
    {

        $campos = '[
            {"campo":"codigoImpuestoPk",                 "tipo":"pk", "ayuda":"codigo del registro",                   "titulo":"ID"},
            {"campo":"nombre",                         "tipo":"texto", "ayuda":"Nombre del impuesto",                  "titulo":"NOMBRE"},
            {"campo":"porcentaje",                      "tipo":"texto", "ayuda":"Direccion",                          "titulo":"DIRECCION"},
            {"campo":"base",                       "tipo":"texto", "ayuda":"Telefono",                           "titulo":"TELEFONO"},
            {"campo":"impuestoTipoRel.nombre",       "tipo":"texto"  ,"ayuda":"Tipo de impuesto",         "titulo":"IMPUESTO_TIPO", "relacion":""},
            {"campo":"codigoCuentaFk",                        "tipo":"texto", "ayuda":"Celular",                            "titulo":"CELULAR"},
            {"campo":"codigoCuentaDevolucionFk",                          "tipo":"texto", "ayuda":"Email",                              "titulo":"EMAIL"}
        ]';

        return $campos;
    }
}
