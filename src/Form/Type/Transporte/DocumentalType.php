<?php

namespace App\Form\Type\Transporte;

use App\Entity\Transporte\TteDocumental;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DocumentalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('comentario',TextareaType::class, array('required' => false))
            ->add('guardar', SubmitType::class, ['label'=>'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TteDocumental::class,
        ]);
    }

    public function getEstructuraPropiedadesLista()
    {
        $campos = '[
            {"campo":"codigoDocumentalPk",                      "tipo":"pk",        "ayuda":"Codigo documental",                        "titulo":"ID"},
            {"campo":"fecha",                                   "tipo":"fecha",     "ayuda":"Fecha",                                    "titulo":"FECHA"},
            {"campo":"comentario",                              "tipo":"texto",     "ayuda":"Comentario",                               "titulo":"COMENTARIO"},            
            {"campo":"estadoAutorizado",                        "tipo":"bool",      "ayuda":"Autorizado",                               "titulo":"AUT"},
            {"campo":"estadoAprobado",                          "tipo":"bool",      "ayuda":"Aprobado",                                 "titulo":"APR"},
            {"campo":"estadoAnulado",                           "tipo":"bool",      "ayuda":"Anulado",                                  "titulo":"ANU"}
        ]';
        return $campos;

    }

    public function getEstructuraPropiedadesFiltro()
    {

        $campos = '[
            {"child":"codigoDocumentalPk",                 "tipo":"TextType",   "propiedades":{"label":"Codigo"}},
                        {"child":"estadoAutorizado",                "tipo":"ChoiceType",   "propiedades":{"label":"Autorizado",     "choices":{"SI":true,"NO":false}}},
            {"child":"estadoAprobado",                  "tipo":"ChoiceType",   "propiedades":{"label":"Aprobado",       "choices":{"SI":true,"NO":false}}},
            {"child":"estadoAnulado",                   "tipo":"ChoiceType",   "propiedades":{"label":"Anulado",        "choices":{"SI":true,"NO":false}}}
        ]';
        return $campos;
    }
}
