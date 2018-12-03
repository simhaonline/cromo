<?php

namespace App\Form\Type\Transporte;

use App\Entity\Transporte\TteCumplido;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CumplidoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('cumplidoTipoRel',EntityType::class,[
                'required' => false,
                'class' => 'App\Entity\Transporte\TteCumplidoTipo',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('ct')
                        ->orderBy('ct.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Cumplido tipo:',
                'required' => true
            ])
            ->add('comentario',TextareaType::class, array('required' => false))
            ->add('guardar', SubmitType::class, ['label'=>'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']])
            ->add('guardarnuevo', SubmitType::class,array('label'=>'Guardar y nuevo'));
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TteCumplido::class,
        ]);
    }

    public function getEstructuraPropiedadesLista()
    {
        $campos = '[
            {"campo":"codigoCumplidoPk",                        "tipo":"pk",        "ayuda":"Codigo cumplido",                          "titulo":"ID"},
            {"campo":"cumplidoTipoRel.nombre",                  "tipo":"texto",     "ayuda":"Tipo cumplido",                            "titulo":"TIPO",                "relacion":""},
            {"campo":"clienteRel.nombreCorto",                  "tipo":"texto",     "ayuda":"Nombre del cliente",                       "titulo":"CLIENTE",             "relacion":""},
            {"campo":"fecha",                                   "tipo":"fecha",     "ayuda":"Fecha",                                    "titulo":"FECHA"},
            {"campo":"comentario",                              "tipo":"entero",    "ayuda":"Comentario",                               "titulo":"COMENTARIOS"},
            {"campo":"estadoAutorizado",                        "tipo":"bool",      "ayuda":"Autorizado",                               "titulo":"AUT"},
            {"campo":"estadoAprobado",                          "tipo":"bool",      "ayuda":"Aprobado",                                 "titulo":"APR"},
            {"campo":"estadoAnulado",                           "tipo":"bool",      "ayuda":"Anulado",                                  "titulo":"ANU"}
        ]';
        return $campos;

    }

    public function getEstructuraPropiedadesFiltro()
    {

        $campos = '[
            {"child":"codigoClienteFk",                 "tipo":"TextType",   "propiedades":{"label":"Cliente"}},
            {"child":"estadoAutorizado",                "tipo":"ChoiceType",   "propiedades":{"label":"Autorizado",     "choices":{"SI":true,"NO":false}}},
            {"child":"estadoAprobado",                  "tipo":"ChoiceType",   "propiedades":{"label":"Aprobado",       "choices":{"SI":true,"NO":false}}},
            {"child":"estadoAnulado",                   "tipo":"ChoiceType",   "propiedades":{"label":"Anulado",        "choices":{"SI":true,"NO":false}}}
        ]';

        return $campos;
    }
}
