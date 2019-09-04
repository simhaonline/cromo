<?php

namespace App\Form\Type\RecursoHumano;

use App\Entity\RecursoHumano\RhuEmbargo;
use App\Entity\RecursoHumano\RhuEmbargoTipo;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class EmbargoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('numero', TextType::class, ['required' => false])
            ->add('codigoEmpleadoFk',TextType::class,['required' => true])
            ->add('embargoTipoRel', EntityType::class, [
                'class' => RhuEmbargoTipo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('er')
                        ->orderBy('er.nombre', 'ASC');
                }, 'choice_label' => 'nombre'])
            ->add('codigoEmbargoJuzgadoFk',TextType::class,['required' => true])
            ->add('VrMontoMaximo', NumberType::class, ['required' => false])
            ->add('fechaInactivacion', DateType::class, ['required' => true, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => ['class' => 'date',]])
            ->add('cuenta', TextType::class, ['required' => false])
            ->add('tipoCuenta', TextType::class, ['required' => false])
            ->add('numeroExpediente', TextType::class, ['required' => false])
            ->add('numeroProceso', TextType::class, ['required' => false])
            ->add('numeroRadicado', TextType::class, ['required' => false])
            ->add('oficio', TextType::class, ['required' => false])
            ->add('oficioInactivacion', TextType::class, ['required' => false])
            ->add('fechaInicioFolio', DateType::class, ['required' => true, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => ['class' => 'date',]])
            ->add('vrValor', NumberType::class, ['required' => true])
            ->add('porcentaje', NumberType::class, ['required' => false])
            ->add('valorFijo', CheckboxType::class, ['required' => false])
            ->add('porcentajeDevengado', CheckboxType::class, ['required' => false])
            ->add('porcentajeDevengadoPrestacional', CheckboxType::class, ['required' => false])
            ->add('porcentajeDevengadoPrestacionalMenosDescuentoLey', CheckboxType::class, ['required' => false])
            ->add('porcentajeDevengadoPrestacionalMenosDescuentoLeyTransporte', CheckboxType::class, ['required' => false])
            ->add('porcentajeDevengadoMenosDescuentoLey', CheckboxType::class, ['required' => false])
            ->add('porcentajeDevengadoMenosDescuentoLeyTransporte', CheckboxType::class, ['required' => false])
            ->add('porcentajeExcedaSalarioMinimo', CheckboxType::class, ['required' => false])
            ->add('porcentajeSalarioMinimo', CheckboxType::class, ['required' => false])
            ->add('estadoActivo', CheckboxType::class, ['required' => false])
            ->add('validarMontoMaximo', CheckboxType::class, ['required' => false])
            ->add('partesExcedaSalarioMinimo', CheckboxType::class, ['required' => false])
            ->add('partesExcedaSalarioMinimoMenosDescuentoLey', CheckboxType::class, ['required' => false])
            ->add('partes', NumberType::class, ['required' => false])
            ->add('numeroIdentificacionDemandante', TextType::class, ['required' => false])
            ->add('nombreCortoDemandante', TextType::class, ['required' => false])
            ->add('apellidosDemandante', TextType::class, ['required' => false])
            ->add('numeroIdentificacionBeneficiario', TextType::class, ['required' => false])
            ->add('oficinaDestino', TextType::class, ['required' => false])
            ->add('consecutivoJuzgado', TextType::class, ['required' => false])
            ->add('codigoInstancia', TextType::class, ['required' => false])
            ->add('nombreCortoBeneficiario', TextType::class, ['required' => false])
            ->add('afectaNomina', CheckboxType::class, ['required' => false, 'label' => 'Nomina'])
            ->add('afectaVacacion', CheckboxType::class, ['required' => false, 'label' => 'Vacacion'])
            ->add('afectaPrima', CheckboxType::class, ['required' => false, 'label' => 'Prima'])
            ->add('afectaLiquidacion', CheckboxType::class, ['required' => false, 'label' => 'Liquidacion'])
            ->add('afectaCesantia', CheckboxType::class, ['required' => false, 'label' => 'Cesantia'])
            ->add('afectaIndemnizacion', CheckboxType::class, ['required' => false, 'label' => 'Indemnizacion'])
            ->add('comentarios', TextareaType::class, ['required' => false])
            ->add('guardar', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RhuEmbargo::class,
        ]);
    }

    public function getEstructuraPropiedadesLista()
    {
        $campos = '[
            {"campo":"codigoEmbargoPk", "tipo":"pk"   ,"ayuda":"Codigo del registro","titulo":"ID"},
            {"campo":"embargoTipoRel.nombre", "tipo":"texto"   ,"ayuda":"Tipo de embargo","titulo":"TIPO","relacion":""},
            {"campo":"numero", "tipo":"texto"   ,"ayuda":"Numero","titulo":"NUMERO"},
            {"campo":"empleadoRel.numeroIdentificacion", "tipo":"texto" ,"ayuda":"Numero de identificación del empleado","titulo":"IDENTIFICACIÓN", "relacion":""},
            {"campo":"empleadoRel.nombreCorto", "tipo":"texto" ,"ayuda":"Nombre del empleado", "titulo":"EMPLEADO","relacion":""},
            {"campo":"fecha",              "tipo":"fecha","ayuda":"Fecha de registro"  ,"titulo":"FECHA"},
            {"campo":"vrValor",            "tipo":"moneda", "ayuda":"Valor del embargo"  ,"titulo":"VALOR"},
            {"campo":"porcentajeDevengado","tipo":"bool", "ayuda":"Porcentaje devengado"  ,"titulo":"%DEV"},
            {"campo":"porcentajeDevengadoPrestacional","tipo":"bool", "ayuda":"Porcentaje devengado prestacional","titulo":"%DEV (PRES)"},
            {"campo":"porcentajeExcedaSalarioMinimo","tipo":"bool"  ,"ayuda":"Porcentaje exceda salario minimo","titulo":"%EXEDA SAL.MIN"},
            {"campo":"porcentaje"                 ,"tipo":"moneda","ayuda":"Porcentaje","titulo":"%"},
            {"campo":"partesExcedaSalarioMinimo"    ,"tipo":"bool"  ,"ayuda":"Partes que excedan salario minimo" ,"titulo":"PARTES"},
            {"campo":"partes"                       ,"tipo":"texto" ,"ayuda":"Numero de partes"                  ,"titulo":"N.PARTES"},
            {"campo":"estadoActivo"                 ,"tipo":"bool"  ,"ayuda":"Estado activo"                            ,"titulo":"ACT"}
        ]';
        return $campos;
    }

    public function getEstructuraPropiedadesFiltro()
    {
        $campos = '[
            {"child":"codigoEmbargoPk", "tipo":"TextType",   "propiedades":{"label":"Codigo"}},
            {"child":"codigoEmbargoTipoFk",       "tipo":"EntityType", "propiedades":{"class":"RhuEmbargoTipo","choice_label":"nombre","label":"Tipo"}},
            {"child":"numero",              "tipo":"TextType",   "propiedades":{"label":"Numero"}},
            {"child":"codigoEmpleadoFk",    "tipo":"TextType",   "propiedades":{"label":"Empleado"}},
            {"child":"fechaDesde",          "tipo":"DateType",   "propiedades":{"label":"Fecha Desde"}},
            {"child":"fechaHasta",          "tipo":"DateType",   "propiedades":{"label":"Fecha Hasta"}},
            {"child":"estadoActivo",    "tipo":"ChoiceType", "propiedades":{"label":"Activo",     "choices":{"SI":true,"NO":false}}}
        ]';

        return $campos;
    }
}