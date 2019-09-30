<?php

namespace App\Form\Type\Cartera;

use App\Entity\Cartera\CarAnticipo;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnticipoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('cuentaRel',EntityType::class,[
                'required' => true,
                'class' => 'App\Entity\General\GenCuenta',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Cuenta:'
            ])
            ->add('anticipoTipoRel',EntityType::class,[
                'required' => true,
                'class' => 'App\Entity\Cartera\CarAnticipoTipo',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('at')
                        ->orderBy('at.orden', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Tipo anticipo:'
            ])
            ->add('asesorRel',EntityType::class,[
                'required' => false,
                'class' => 'App\Entity\General\GenAsesor',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('a')
                        ->orderBy('a.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Asesor:'
            ])
            ->add('soporte', TextType::class, array('required' => false))
            ->add('fechaPago', DateType::class, array('format' => 'yyyyMMdd'))
            ->add('comentarios',TextareaType::class,['required' => false,'label' => 'Comentarios:'])
            ->add('guardar', SubmitType::class, ['label'=>'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']])
            ->add('guardarnuevo', SubmitType::class, ['label'=>'Guardar y nuevo','attr' => ['class' => 'btn btn-sm btn-primary']]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CarAnticipo::class,
        ]);
    }


    public function getEstructuraPropiedadesFiltro()
    {

        $campos = '[
            {"child":"codigoClienteFk",    "tipo":"TextType",  "propiedades":{"label":"Cliente"}},
            {"child":"numero",             "tipo":"TextType",  "propiedades":{"label":"Numero"}},
            {"child":"codigoAnticipoPk",     "tipo":"TextType",  "propiedades":{"label":"Codigo"}},
            {"child":"codigoAnticipoTipoFk", "tipo":"EntityType","propiedades":{"class":"CarReciboTipo","choice_label":"nombre", "label":"TODOS"}},
            {"child":"codigoAsesorFk",     "tipo":"EntityType","propiedades":{"class":"GenAsesor","choice_label":"nombre", "label":"TODOS"}},
            {"child":"fechaDesde",         "tipo":"DateType",  "propiedades":{"label":"Fecha Desde"}},
            {"child":"fechaHasta",         "tipo":"DateType",  "propiedades":{"label":"Fecha Hasta"}},
            {"child":"estadoAutorizado",   "tipo":"ChoiceType","propiedades":{"label":"Autorizado",     "choices":{"SI":true,"NO":false}}},
            {"child":"estadoAprobado",     "tipo":"ChoiceType","propiedades":{"label":"Aprobado",       "choices":{"SI":true,"NO":false}}},
            {"child":"estadoAnulado",      "tipo":"ChoiceType","propiedades":{"label":"Anulado",        "choices":{"SI":true,"NO":false}}}
        ]';

        return $campos;
    }
}
