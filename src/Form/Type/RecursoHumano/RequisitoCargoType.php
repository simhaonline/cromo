<?php

namespace App\Form\Type\RecursoHumano;

use App\Entity\RecursoHumano\RhuCargo;
use App\Entity\RecursoHumano\RhuRequisitoCargo;
use App\Entity\RecursoHumano\RhuRequisitoConcepto;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RequisitoCargoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('cargoRel', EntityType::class, array(
                'class' => RhuCargo::class,
                'choice_label' => 'nombre',
            ))
            ->add('guardar', SubmitType::class, array('label' => 'Guardar',))
            ->getForm();
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RhuRequisitoCargo::class,
        ]);
    }

    public function getEstructuraPropiedadesLista()
    {
        $campos = '[
            {"campo":"codigoRequisitoCargoPk",              "tipo":"pk"      ,"ayuda":"Codigo del registro"                ,"titulo":"ID"},
            {"campo":"cargoRel.nombre",                     "tipo":"texto"   ,"ayuda":""                     ,              "titulo":"CARGO", "relacion":""},
            {"campo":"requisitoConceptoRel.nombre",         "tipo":"texto",   "ayuda":"",                                   "titulo":"REQUISITO", "relacion":""},
            {"campo":"requisitoConceptoRel.general",        "tipo":"bool",   "ayuda":"",                                   "titulo":"GENERAL", "relacion":""}
        ]';
        return $campos;
    }

    public function getEstructuraPropiedadesFiltro()
    {
        $campos = '[
          
        ]';
        return $campos;
    }
}
