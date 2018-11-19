<?php

namespace App\Form\Type\RecursoHumano;

use App\Entity\RecursoHumano\RhuEgresoTipo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EgresoTipoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoEgresoTipoPk', TextType::class, ['required' => true, 'label' => 'Codigo egreso tipo:'])
            ->add('nombre', TextType::class, ['required' => 'true', 'label' => 'Nombre:'])
            ->add('codigoCuentaFk', TextType::class, ['required' => false, 'label' => 'Cuenta:'])
            ->add('guardar', SubmitType::class, ['label' => 'Guardar', 'attr' => ['class' => 'btn btn-sm btn-primary']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RhuEgresoTipo::class,
        ]);
    }

    public function getEstructuraPropiedadesLista()
    {
        $campos = '[
            {"campo":"codigoEgresoTipoPk", "tipo":"pk",    "ayuda":"Codigo del registro",                    "titulo":"ID"},
            {"campo":"nombre",             "tipo":"texto", "ayuda":"Nombre del registro",                    "titulo":"NOMBRE"},
            {"campo":"codigoCuentaFk",     "tipo":"texto", "ayuda":"Numero de cuenta a la que esta asociada","titulo":"CUENTA"}
        ]';
        return $campos;
    }

    public function getEstructuraPropiedadesExportar()
    {
        $campos = '[
            {"campo":"codigoEgresoTipoPk", "tipo":"pk",    "ayuda":"Codigo del registro",                    "titulo":"ID"},
            {"campo":"nombre",             "tipo":"texto", "ayuda":"Nombre del registro",                    "titulo":"NOMBRE"},
            {"campo":"codigoCuentaFk",     "tipo":"texto", "ayuda":"Numero de cuenta a la que esta asociada","titulo":"CUENTA"}
        ]';
        return $campos;
    }
}
