<?php

namespace App\Form\Type\RecursoHumano;

use App\Entity\General\GenCiudad;
use App\Entity\RecursoHumano\RhuCargo;
use App\Entity\RecursoHumano\RhuCentroTrabajo;
use App\Entity\RecursoHumano\RhuClasificacionRiesgo;
use App\Entity\RecursoHumano\RhuContrato;
use App\Entity\RecursoHumano\RhuContratoTipo;
use App\Entity\RecursoHumano\RhuCostoClase;
use App\Entity\RecursoHumano\RhuCostoGrupo;
use App\Entity\RecursoHumano\RhuEntidad;
use App\Entity\RecursoHumano\RhuGrupo;
use App\Entity\RecursoHumano\RhuPension;
use App\Entity\RecursoHumano\RhuSalud;
use App\Entity\RecursoHumano\RhuSubtipoCotizante;
use App\Entity\RecursoHumano\RhuTiempo;
use App\Entity\RecursoHumano\RhuTipoCotizante;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContratoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fechaDesde', DateType::class, ['required' => true, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => ['class' => 'date',]])
            ->add('fechaHasta', DateType::class, ['required' => true, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => ['class' => 'date',]])
            ->add('cargoDescripcion',TextType::class,['required' => false])
            ->add('vrSalario',NumberType::class,['required' => true])
            ->add('numero',TextType::class,['required' => false])
            ->add('codigoCostoTipoFk',ChoiceType::class,['required' => true, 'choices' => ['DISTRIBUIDO' => 'DIS','FIJO' => 'FIJ', 'OPERATIVO' => 'OPE']])
            ->add('salarioIntegral',CheckboxType::class,['required' => false, 'label' => 'Salario integral'])
            ->add('auxilioTransporte',CheckboxType::class,['required' => false, 'label' => 'Auxilio transporte'])
            ->add('contratoTipoRel', EntityType::class, [
                'class' => RhuContratoTipo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('r')
                        ->orderBy('r.orden', 'ASC');
                },
                'choice_label' => 'nombre',
                'required' => true
            ])
            ->add('costoGrupoRel', EntityType::class, [
                'class' => RhuCostoGrupo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('r')
                        ->orderBy('r.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'required' => true
            ])
            ->add('clasificacionRiesgoRel', EntityType::class, [
                'class' => RhuClasificacionRiesgo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('r')
                        ->orderBy('r.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'required' => true
            ])
            ->add('tiempoRel', EntityType::class, [
                'class' => RhuTiempo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('r')
                        ->orderBy('r.orden', 'ASC');
                },
                'choice_label' => 'nombre',
                'required' => true
            ])
            ->add('pensionRel', EntityType::class, [
                'class' => RhuPension::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('r')
                        ->orderBy('r.orden', 'ASC');
                },
                'choice_label' => 'nombre',
                'required' => true
            ])
            ->add('costoClaseRel', EntityType::class, [
                'class' => RhuCostoClase::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('r')
                        ->orderBy('r.orden', 'ASC');
                },
                'choice_label' => 'nombre',
                'required' => true
            ])
            ->add('saludRel', EntityType::class, [
                'class' => RhuSalud::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('r')
                        ->orderBy('r.orden', 'ASC');
                },
                'choice_label' => 'nombre',
                'required' => true
            ])
            ->add('cargoRel', EntityType::class, [
                'class' => RhuCargo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('r')
                        ->orderBy('r.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'required' => false
            ])
            ->add('grupoRel', EntityType::class, [
                'class' => RhuGrupo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('r')
                        ->orderBy('r.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'required' => true
            ])
            ->add('tipoCotizanteRel', EntityType::class, [
                'class' => RhuTipoCotizante::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('r')
                        ->orderBy('r.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'required' => true
            ])
            ->add('subtipoCotizanteRel', EntityType::class, [
                'class' => RhuSubtipoCotizante::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('r')
                        ->orderBy('r.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'required' => true
            ])
            ->add('entidadSaludRel', EntityType::class, [
                'class' => RhuEntidad::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('r')
                        ->where('r.eps = 1')
                        ->orderBy('r.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'required' => true
            ])
            ->add('entidadPensionRel', EntityType::class, [
                'class' => RhuEntidad::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('r')
                        ->where('r.pen = 1')
                        ->orderBy('r.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'required' => true
            ])
            ->add('entidadCesantiaRel', EntityType::class, [
                'class' => RhuEntidad::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('r')
                        ->where('r.ces = 1')
                        ->orderBy('r.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'required' => true
            ])
            ->add('entidadCajaRel', EntityType::class, [
                'class' => RhuEntidad::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('r')
                        ->where('r.ccf = 1')
                        ->orderBy('r.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'required' => true
            ])
            ->add('ciudadContratoRel', EntityType::class, [
                'class' => GenCiudad::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('r')
                        ->orderBy('r.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'required' => false
            ])
            ->add('ciudadLaboraRel', EntityType::class, [
                'class' => GenCiudad::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('r')
                        ->orderBy('r.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'required' => false
            ])
            ->add('centroTrabajoRel', EntityType::class, [
                'class' => RhuCentroTrabajo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('r')
                        ->orderBy('r.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'required' => false
            ])
            ->add('guardar', SubmitType::class, ['label' => 'Guardar', 'attr' => ['class' => 'btn btn-sm btn-primary']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RhuContrato::class,
        ]);
    }

    public function getEstructuraPropiedadesLista()
    {
        $campos = '[
            {"campo":"codigoContratoPk",                    "tipo":"pk"      ,"ayuda":"Codigo del contrato"                   ,"titulo":"ID"},
            {"campo":"contratoTipoRel.nombre",              "tipo":"texto"   ,"ayuda":"Tipo de contrato"                      ,"titulo":"TIPO", "relacion":""},
            {"campo":"empleadoRel.numeroIdentificacion",    "tipo":"texto"   ,"ayuda":"Numero identificacion"                 ,"titulo":"IDENTIFICACION", "relacion":""},
            {"campo":"empleadoRel.nombreCorto",             "tipo":"texto"   ,"ayuda":"Nombre del empleado"                 ,"titulo":"NOMBRE", "relacion":""},
            {"campo":"grupoRel.nombre",                     "tipo":"text"   ,"ayuda":"Grupo del contrato"                    ,"titulo":"GRUPO",        "relacion":""},
            {"campo":"codigoTiempoFk",                      "tipo":"texto"   ,"ayuda":"Tipo de tiempo (Completo, medio tiempo, sabatino)"                   ,"titulo":"TIE"},
            {"campo":"fechaDesde",                          "tipo":"fecha"   ,"ayuda":"Fecha de inicio del contrato"          ,"titulo":"DESDE"},
            {"campo":"fechaHasta",                          "tipo":"fecha"   ,"ayuda":"Fecha de terminacion del cotnrato"     ,"titulo":"HASTA"},
            {"campo":"vrSalario",                           "tipo":"moneda"    ,"ayuda":"Salario actual del empleado"         ,"titulo":"SALARIO"},
            {"campo":"estadoTerminado",                     "tipo":"bool"    ,"ayuda":"El contrato esta terminado o no"       ,"titulo":"TER"}                                                                                                                                                      
        ]';
        return $campos;
    }
}
