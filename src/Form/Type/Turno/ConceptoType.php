<?php

namespace App\Form\Type\Turno;

use App\Entity\Turno\TurCliente;
use App\Entity\Turno\TurConcepto;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConceptoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('nombre', TextType::class, array('required' => true))
            ->add('horas', NumberType::class, array('required' => true))
            ->add('horasDiurnas', NumberType::class, array('required' => true))
            ->add('horasNocturnas', NumberType::class, array('required' => true))
            ->add('guardar', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TurConcepto::class,
        ]);
    }

    public function getEstructuraPropiedadesLista()
    {
        $campos = '[
            {"campo":"codigoConceptoPk",       "tipo":"pk"      ,"ayuda":"Codigo del registro"                      ,"titulo":"ID"},
            {"campo":"nombre",                 "tipo":"texto"   ,"ayuda":"",                                         "titulo":"NOMBRE"},            
            {"campo":"horas",                  "tipo":"texto"   ,"ayuda":"Horas"                                         ,"titulo":"H"},
            {"campo":"horasDiurnas",           "tipo":"texto"   ,"ayuda":"Horas diurnas"                                         ,"titulo":"HD"},
            {"campo":"horasNocturnas",         "tipo":"texto"   ,"ayuda":"Horas nocturnas"                                         ,"titulo":"HN"},
            {"campo":"porcentajeIva",          "tipo":"texto"   ,"ayuda":"Porcentaje IVA"                           ,"titulo":"IVA"}
        ]';
        return $campos;
    }

    public function getEstructuraPropiedadesFiltro()
    {
        $campos = '[
            {"child":"codigoConceptoPk",     "tipo":"TextType",    "propiedades":{"label":"Codigo"}},
            {"child":"nombre",               "tipo":"TextType",    "propiedades":{"label":"Nombre"}}
        ]';
        return $campos;
    }
}

