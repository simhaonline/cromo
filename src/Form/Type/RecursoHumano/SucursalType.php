<?php


namespace App\Form\Type\RecursoHumano;


use App\Entity\RecursoHumano\RhuRequisitoTipo;
use App\Entity\RecursoHumano\RhuSucursal;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SucursalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoSucursalPk', TextType::class,['required'=>true])
            ->add('nombre', TextType::class,['required'=>true])
            ->add('codigo', TextType::class,['required'=>true])
            ->add('estadoActivo', CheckboxType::class, ['required' => false])
            ->add('guardar', SubmitType::class, ['label'=>'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RhuSucursal::class,
        ]);
    }

    public function getEstructuraPropiedadesLista()
    {
        $campos = '[
            {"campo":"codigoSucursalPk",    "tipo":"pk"      ,"ayuda":"Codigo de la sucursal", "titulo":"ID"},
            {"campo":"nombre",              "tipo":"texto"   ,"ayuda":"Nombre sucursal",    "titulo":"nombre"},
            {"campo":"estadoActivo",                           "tipo":"bool",      "ayuda":"Activo",  "titulo":"ACT"}
        ]';
        return $campos;
    }

    public function getEstructuraPropiedadesFiltro()
    {
        $campos = '[
            {"child":"codigoSucursalPk", "tipo":"TextType",   "propiedades":{"label":"Codigo"}},
            {"child":"nombre",            "tipo":"TextType",   "propiedades":{"label":"Nombre"}},
            {"child":"estadoActivo",                  "tipo":"ChoiceType",    "propiedades":{"label":"Activo",       "choices":{"SI":true,"NO":false}}}

        ]';
        return $campos;
    }
}