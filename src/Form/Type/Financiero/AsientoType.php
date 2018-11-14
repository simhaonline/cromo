<?php

namespace App\Form\Type\Financiero;

use App\Entity\Financiero\FinAsiento;
use App\Entity\Financiero\FinComprobante;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AsientoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('comprobanteRel', EntityType::class, [
                'required' => true,
                'class' => FinComprobante::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Comprobante:'
            ])
            ->add('fecha', DateType::class, ['label' => 'Fecha:'])
            ->add('numero', NumberType::class, ['label' => 'Numero:'])
            ->add('fechaContable', DateType::class, ['label' => 'Fecha contable:'])
            ->add('fechaDocumento', DateType::class, ['label' => 'Fecha documento:'])
            ->add('comentario', TextareaType::class, ['label' => 'Comentario:', 'required' => false])
            ->add('guardar', SubmitType::class, ['label' => 'Guardar', 'attr' => ['class' => 'btn btn-sm btn-primary']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FinAsiento::class,
        ]);
    }

    public function getEstructuraPropiedadesLista()
    {
        $campos = '[
            {"campo":"codigoAsientoPk",     "tipo":"pk",        "ayuda":"Codigo del asiento",   "titulo":"ID"},
            {"campo":"numero",              "tipo":"entero",    "ayuda":"",                     "titulo":"NUMERO"},
            {"campo":"codigoComprobanteFk", "tipo":"texto",     "ayuda":"",                     "titulo":"COMP"},
            {"campo":"fecha",               "tipo":"fecha",     "ayuda":"Fecha de registro",    "titulo":"FECHA"},
            {"campo":"fechaContable",       "tipo":"fecha",     "ayuda":"Fecha de contabilidad","titulo":"F_CONT"},
            {"campo":"fechaDocumento",      "tipo":"fecha",     "ayuda":"Fecha de elaboracion", "titulo":"F_DOC"},
            {"campo":"vrDebito",            "tipo":"moneda",    "ayuda":"",                     "titulo":"DEBITO"},
            {"campo":"vrCredito",           "tipo":"moneda",    "ayuda":"",                     "titulo":"CREDITO"},
            {"campo":"estadoAutorizado",    "tipo":"bool",      "ayuda":"",                     "titulo":"AUT"},
            {"campo":"estadoAprobado",      "tipo":"bool",      "ayuda":"",                     "titulo":"APR"},
            {"campo":"estadoAnulado",       "tipo":"bool",      "ayuda":"",                     "titulo":"ANU"}                                
        ]';
        return $campos;
    }

    public function getEstructuraPropiedadesFiltro()
    {

        $campos = '[
            {"child":"numero", "tipo":"TextType", "propiedades":{"label":"Numero"}},
            {"child":"codigoComprobanteFk","tipo":"EntityType","propiedades":{"class":"FinComprobante","choice_label":"nombre","label":"comprobante"}},
            {"child":"estadoAutorizado","tipo":"CheckboxType","propiedades":{"label":"Autorizado"}},
            {"child":"estadoAprobado","tipo":"CheckboxType","propiedades":{"label":"Aprobado"}},
            {"child":"estadoAnulado","tipo":"CheckboxType","propiedades":{"label":"Anulado"}},
            {"child":"filtrarFecha","tipo":"CheckboxType","propiedades":{"label":"Filtrar Fecha"}},
            {"child":"fechaDesde","tipo":"DateType","propiedades":{"label":"Fecha Desde"}},
            {"child":"fechaHasta","tipo":"DateType","propiedades":{"label":"Fecha Hasta"}}
        ]';

        return $campos;
    }
}
