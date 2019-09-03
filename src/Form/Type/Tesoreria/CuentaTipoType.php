<?php


namespace App\Form\Type\Tesoreria;


use App\Entity\Tesoreria\TesCuentaTipo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CuentaTipoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre', TextType::class, ['required' => true , 'label' => 'Nombre:'])
            ->add('guardar', SubmitType::class, ['label' => 'Guardar']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TesCuentaTipo::class,
        ]);
    }

    public function getEstructuraPropiedadesLista()
    {
        $campos = '[
            {"campo":"codigoCuentaTipoPK",            "tipo":"pk",    "ayuda":"Codigo del registro",             "titulo":"ID"},
            {"campo":"nombre",                         "tipo":"texto", "ayuda":"Nombre",            "titulo":"NOMBRE"}
        ]';
        return $campos;
    }

    public function getEstructuraPropiedadesFiltro()
    {
        $campos = '[
            {"child":"codigoCuentaTipoPK", "tipo":"TextType",  "propiedades":{"label":"Codigo"}},
            {"child":"nombre",   "tipo":"TextType",  "propiedades":{"label":"nombre"}}
        ]';

        return $campos;
    }
}