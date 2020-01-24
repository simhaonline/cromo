<?php


namespace App\Form\Type\Turno;


use App\Entity\Turno\TurZona;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ZonaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre', TextType::class, ['required' => true, 'label'=>'Nombre'])
            ->add('guardar', SubmitType::class, array('label' => 'Guardar',))
            ->getForm();
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TurZona::class,
        ]);
    }

    public function getEstructuraPropiedadesLista(){
        return '[	
            {"campo":"codigoZonaPk",            "tipo":"pk",        "ayuda":"Codigo del registro",      "titulo":"ID"},	
            {"campo":"nombre",                      "tipo":"texto",     "ayuda":"Nombre del registro",      "titulo":"NOMBRE"}
        ]';
    }

    public function getEstructuraPropiedadesExportar()
    {
        $campos = '[
            {"campo":"codigoZonaPk", "tipo":"pk",    "ayuda":"Codigo del registro",                    "titulo":"ID"},
            {"campo":"nombre",             "tipo":"texto", "ayuda":"Nombre del registro",                    "titulo":"NOMBRE"}
        ]';
        return $campos;
    }
}