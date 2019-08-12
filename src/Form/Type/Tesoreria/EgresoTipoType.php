<?php

namespace App\Form\Type\Tesoreria;


use App\Entity\Tesoreria\TesEgresoTipo;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EgresoTipoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('codigoEgresoTipoPk',TextType::class,['required' => true,'label' => 'Codigo egreso tipo:'])
            ->add('nombre',TextType::class,['required' => true,'label' => 'Nombre:'])
            ->add('guardar', SubmitType::class, ['label'=>'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TesEgresoTipo::class,
        ]);
    }

    public function getEstructuraPropiedadesLista()
    {
        $campos = '[
            {"campo":"codigoEgresoTipoPk",            "tipo":"pk",        "ayuda":"Codigo del registro",     "titulo":"ID"},
            {"campo":"nombre",                          "tipo":"texto",     "ayuda":"Nombre del registro",     "titulo":"NOMBRE"}                             
        ]';
        return $campos;
    }

    public function getEstructuraPropiedadesFiltro()
    {
        $campos = '[            
            {"child":"nombre",  "tipo":"TextType",    "propiedades":{"label":"Nombre"}},
        ]';
        return $campos;
    }
}