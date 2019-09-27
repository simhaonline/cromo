<?php

namespace App\Form\Type\Cartera;

use App\Entity\Cartera\CarCompromiso;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompromisoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fechaCompromiso', DateType::class,array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('comentarios',TextareaType::class,['required' => false,'label' => 'Comentarios:'])
            ->add('guardar', SubmitType::class, ['label'=>'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CarCompromiso::class,
        ]);
    }



    public function getEstructuraPropiedadesFiltro()
    {

        $campos = '[
            {"child":"codigoClienteFk",    "tipo":"TextType",  "propiedades":{"label":"Cliente"}},
            {"child":"codigoCompromisoPk",     "tipo":"TextType",  "propiedades":{"label":"Codigo"}},
            {"child":"fechaCompromisoDesde",         "tipo":"DateType",  "propiedades":{"label":"Fecha Desde"}},
            {"child":"fechaCompromisoHasta",         "tipo":"DateType",  "propiedades":{"label":"Fecha Hasta"}},
            {"child":"estadoAutorizado",   "tipo":"ChoiceType","propiedades":{"label":"Autorizado",     "choices":{"SI":true,"NO":false}}},
            {"child":"estadoAprobado",     "tipo":"ChoiceType","propiedades":{"label":"Aprobado",       "choices":{"SI":true,"NO":false}}},
            {"child":"estadoAnulado",      "tipo":"ChoiceType","propiedades":{"label":"Anulado",        "choices":{"SI":true,"NO":false}}}
        ]';

        return $campos;
    }
}
