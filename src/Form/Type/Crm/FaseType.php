<?php


namespace App\Form\Type\Crm;


use App\Entity\Crm\CrmFase;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FaseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoFasePk',TextType::class,['required' => true,'label' => 'Codigo face:'])
            ->add('nombre',TextType::class,['required' => true,'label' => 'Nombre:'])
            ->add('guardar', SubmitType::class, ['label'=>'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CrmFase::class,
        ]);
    }

    public function getEstructuraPropiedadesLista()
    {
        $campos = '[
            {"campo":"codigoFasePk",    "tipo":"pk",        "ayuda":"Codigo",               "titulo":"ID"},
            {"campo":"nombre",          "tipo":"texto",     "ayuda":"Nombre",               "titulo":"NOMBRE"}
        ]';
        return $campos;
    }
    public function getEstructuraPropiedadesFiltro()
    {

        $campos = '[
            {"child":"nombre",      "tipo":"TextType",   "propiedades":{"label":"Nombre"},   "operador":"like"},
            {"child":"codigoFasePk",      "tipo":"TextType",   "propiedades":{"label":"Identificacion"},   "operador":"like"}
        ]';

        return $campos;
    }
}