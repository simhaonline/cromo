<?php


namespace App\Form\Type\RecursoHumano;


use App\Entity\RecursoHumano\RhuDotacionElemento;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DotacionElementoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre', TextType::class, ['required' => true])
            ->add('guardar', SubmitType::class, ['attr' => ['class' => 'btn btn-sm btn-primary']]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' =>  RhuDotacionElemento ::class,
        ]);
    }

    public function getEstructuraPropiedadesLista()
    {
        $campos = '[
            {"campo":"codigoDotacionElementoPk", "tipo":"pk"     ,"ayuda":"Codigo del empleado" ,"titulo":"ID"},
            {"campo":"nombre",                   "tipo":"texto"  ,"ayuda":"nombre"              ,"titulo":"nombre"}
        ]';
        return $campos;
    }

    public function getEstructuraPropiedadesFiltro()
    {
        $campos = '[
            {"child":"nombre",    "tipo":"TextType",   "propiedades":{"label":"nombre"}}

        ]';
        return $campos;
    }

}