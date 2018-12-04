<?php

namespace App\Form\Type\Inventario;

use App\Entity\Inventario\InvSolicitudTipo;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SolicitudType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('solicitudTipoRel',EntityType::class,[
                'required' => true,
                'class' => InvSolicitudTipo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('st')
                        ->orderBy('st.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Solicitud tipo:'
            ])
            ->add('comentarios',TextareaType::class, ['required' => false,'label' => 'Comentarios:'])
            ->add('soporte',TextType::class, ['required' => false,'label' => 'Soporte:'])
            ->add('guardar', SubmitType::class, ['label'=>'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\Inventario\InvSolicitud'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'App_solicitud';
    }

    public function getEstructuraPropiedadesLista()
    {
        $campos = '[
            {"campo":"codigoSolicitudPk",               "tipo":"pk",        "ayuda":"Codigo del registro",                  "titulo":"ID"},
            {"campo":"solicitudTipoRel.nombre",         "tipo":"texto",     "ayuda":"Tipo de solicitud",                    "titulo":"SOLICITUD TIPO",         "relacion":""},
            {"campo":"numero",                          "tipo":"texto",     "ayuda":"Numero del registro",                  "titulo":"NUMERO"},
            {"campo":"fecha",                           "tipo":"fecha",     "ayuda":"Fecha del registro",                   "titulo":"FECHA"},
            {"campo":"usuario",                         "tipo":"texto",     "ayuda":"Usuario",                              "titulo":"USUARIO"},
            {"campo":"estadoAutorizado",                "tipo":"bool",      "ayuda":"Autorizdo",                            "titulo":"AUT"},
            {"campo":"estadoAprobado",                  "tipo":"bool",      "ayuda":"Aprobado",                             "titulo":"APR"},
            {"campo":"estadoAnulado",                   "tipo":"bool",      "ayuda":"Anulado",                              "titulo":"ANU"}
                                     
        ]';
        return $campos;
    }

    public function getEstructuraPropiedadesFiltro()
    {
        $campos = '[
            {"child":"codigoSolicitudPk",       "tipo":"TextType",    "propiedades":{"label":"Codigo"}},
            {"child":"numero",                  "tipo":"TextType",    "propiedades":{"label":"Numero"}},
            {"child":"codigoSolicitudTipoFk",   "tipo":"EntityType",  "propiedades":{"class":"InvSolicitudTipo","choice_label":"nombre","label":"Tipo"}}
        ]';
        return $campos;
    }

}
