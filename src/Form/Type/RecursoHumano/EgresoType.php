<?php

namespace App\Form\Type\RecursoHumano;

use App\Entity\RecursoHumano\RhuEgreso;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EgresoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoEgresoTipoFk')
            ->add('fecha')
            ->add('numero')
            ->add('nombre')
            ->add('codigoCuentaFk')
            ->add('estadoAutorizado')
            ->add('estadoAprobado')
            ->add('estadoAnulado');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RhuEgreso::class,
        ]);
    }

    public function getEstructuraPropiedadesLista()
    {
        $campos = '[
            {"campo":"codigoEgresoPk",    "tipo":"pk",    "ayuda":"Codigo del registro",       "titulo":"ID"},
            {"campo":"codigoEgresoTipoFk","tipo":"texto", "ayuda":"Codigo del tipo de egreso", "titulo":"TIPO"},
            {"campo":"fecha",             "tipo":"fecha", "ayuda":"Fecha de registro",         "titulo":"FECHA"},
            {"campo":"numero",            "tipo":"texto", "ayuda":"Numero",                    "titulo":"NUMERO"},
            {"campo":"nombre",            "tipo":"texto", "ayuda":"Nombre del egreso",         "titulo":"NOMBRE"},
            {"campo":"codigoCuentaFk",    "tipo":"texto", "ayuda":"Codigo de la cuenta",       "titulo":"CUENTA"},
            {"campo":"estadoAutorizado",  "tipo":"bool",  "ayuda":"Estado autorizado",         "titulo":"AUT"},
            {"campo":"estadoAprobado",    "tipo":"bool",  "ayuda":"Estado aprobado",           "titulo":"APR"},
            {"campo":"estadoAnulado",     "tipo":"bool",  "ayuda":"Estado anulado",            "titulo":"ANU"}
        ]';
        return $campos;
    }
}