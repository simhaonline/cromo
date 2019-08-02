<?php

namespace App\Form\Type\Tesoreria;

use App\Entity\General\GenCuenta;
use App\Entity\Tesoreria\TesEgreso;
use App\Entity\Tesoreria\TesEgresoTipo;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EgresoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('comentarios', TextareaType::class, ['label' => 'Comentario:', 'required' => false])
            ->add('egresoTipoRel', EntityType::class, [
                'class' => TesEgresoTipo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('et')
                        ->orderBy('et.nombre', 'ASC');
                },
                'choice_label' => 'nombre'

            ])
            ->add('cuentaRel', EntityType::class, [
                'class' => GenCuenta::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.nombre');
                },
                'choice_label' => 'nombre'
            ])
            ->add('guardar', SubmitType::class, ['label' => 'Guardar', 'attr' => ['class' => 'btn btn-sm btn-primary']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TesEgreso::class,
        ]);
    }

    public function getEstructuraPropiedadesLista()
    {
        $campos = '[
            {"campo":"codigoEgresoPk",      "tipo":"pk",    "ayuda":"Codigo del registro",                 "titulo":"ID"},
            {"campo":"numero",              "tipo":"entero","ayuda":"Numero del consecutivo de aprobación","titulo":"NUMERO"},
            {"campo":"egresoTipoRel.nombre","tipo":"texto", "ayuda":"Tipo de registro",                    "titulo":"TIPO","relacion":""},
            {"campo":"fecha",               "tipo":"fecha", "ayuda":"Fecha de registro",                   "titulo":"FECHA"},
            {"campo":"estadoAutorizado",    "tipo":"bool",  "ayuda":"Estado autorizado",                   "titulo":"AUT"},
            {"campo":"estadoAprobado",      "tipo":"bool",  "ayuda":"Estado aprobado",                     "titulo":"APR"},
            {"campo":"estadoAnulado",       "tipo":"bool",  "ayuda":"Estado anulado",                      "titulo":"ANU"}                                
        ]';
        return $campos;
    }

    public function getEstructuraPropiedadesFiltro()
    {
        $campos = '[
            {"child":"codigoEgresoPk",     "tipo":"TextType",    "propiedades":{"label":"Codigo"}},
            {"child":"codigoTerceroFk",  "tipo":"TextType",    "propiedades":{"label":"Codigo proveedor"}},
            {"child":"numero",             "tipo":"TextType",          "propiedades":{"label":"Numero"}},
            {"child":"codigoEgresoTipoFk", "tipo":"TextType",      "propiedades":{"label":"Codigo egreso tipo"}},
            {"child":"fechaDesde",  "tipo":"DateType",           "propiedades":{"label":"Fecha desde"}},
            {"child":"fechaHasta",  "tipo":"DateType",           "propiedades":{"label":"Fecha hasta"}},
            {"child":"estadoAutorizado",   "tipo":"ChoiceType",  "propiedades":{"label":"Autorizado",     "choices":{"SI":true,"NO":false}}},
            {"child":"estadoAprobado",     "tipo":"ChoiceType",  "propiedades":{"label":"Aprobado",       "choices":{"SI":true,"NO":false}}},
            {"child":"estadoAnulado",      "tipo":"ChoiceType",  "propiedades":{"label":"Anulado",        "choices":{"SI":true,"NO":false}}}
        ]';
        return $campos;
    }
}