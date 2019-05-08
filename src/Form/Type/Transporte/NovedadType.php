<?php

namespace App\Form\Type\Transporte;

use App\Entity\Transporte\TteGuiaTipo;
use App\Entity\Transporte\TteNovedadTipo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

class NovedadType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('novedadTipoRel', EntityType::class, array(
                'class' => TteNovedadTipo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('nt')
                        ->orderBy('nt.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
            ))
            ->add('fecha', DateTimeType::class)
            ->add('fechaReporte', DateTimeType::class)
            ->add('descripcion',TextareaType::class, array('required' => false))
            ->add('estadoAtendido', CheckboxType::class, array('required' => false))
            ->add('aplicaGuia', CheckboxType::class, array('required' => false))
            ->add('guardar', SubmitType::class,array('label'=>'Guardar'));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\Transporte\TteNovedad'
        ));
    }

    public function getEstructuraPropiedadesLista()
    {
        $campos = '[
            {"campo":"codigoNovedadPk",                         "tipo":"pk",        "ayuda":"Codigo de novedad",                        "titulo":"ID"},
            {"campo":"novedadTipoRel.nombre",                   "tipo":"texto",     "ayuda":"Tipo novedad",                             "titulo":"TIPO",                "relacion":""},
            {"campo":"guiaRel.numero",                          "tipo":"entero",    "ayuda":"Numero guia",                              "titulo":"GUIA",                "relacion":""},
            {"campo":"descripcion",                             "tipo":"texto",     "ayuda":"Descripcion",                              "titulo":"DESCRIPCION"},
            {"campo":"solucion",                                "tipo":"texto",     "ayuda":"Solucion",                                 "titulo":"SOLUCION"},
            {"campo":"fechaReporte",                            "tipo":"fecha",     "ayuda":"Fecha reporte",                            "titulo":"FECHA REPORTE"},
            {"campo":"fechaAtencion",                           "tipo":"fecha",     "ayuda":"Fecha atencion",                           "titulo":"FECHA ATENCION"},
            {"campo":"fechaSolucion",                           "tipo":"fecha",     "ayuda":"Fecha solucion",                           "titulo":"FECHA SOLUCION"}
        ]';
        return $campos;

    }

    public function getEstructuraPropiedadesFiltro()
    {

        $campos = '[
            {"child":"guiaRel.numero",                  "tipo":"TextType",   "propiedades":{"label":"Guia"},        "relacion":""},
            {"child":"fechaReporteDesde",                      "tipo":"DateType",   "propiedades":{"label":"Fecha Desde"}},
            {"child":"fechaReporteHasta",                      "tipo":"DateType",   "propiedades":{"label":"Fecha Hasta"}},
            {"child":"codigoNovedadTipoFk",             "tipo":"EntityType", "propiedades":{"class":"TteNovedadTipo",   "choice_label":"nombre",    "label":"TODOS"}}
        ]';

        return $campos;
    }

    public function getOrdenamiento(){
        $campos ='[
            {"campo":"codigoNovedadPk","tipo":"DESC"}
        ]';
        return $campos;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'App_novedad';
    }

}
