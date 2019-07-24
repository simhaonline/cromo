<?php

namespace App\Form\Type\RecursoHumano;

use App\Entity\RecursoHumano\RhuCredito;
use App\Entity\RecursoHumano\RhuCreditoPago;
use App\Entity\RecursoHumano\RhuCreditoPagoTipo;
use App\Entity\RecursoHumano\RhuCreditoTipo;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreditoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoEmpleadoFk', TextType::class, ['required' => true])
            ->add('fechaInicio', DateType::class, ['data' => new \DateTime('now'),'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)])
            ->add('fechaFinalizacion', DateType::class, ['data' => new \DateTime('now'),'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)])
            ->add('fechaCredito', DateType::class, ['data' => new \DateTime('now'),'widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)])
            ->add('numeroCuotas', IntegerType::class, ['required' => true])
            ->add('comentario', TextareaType::class, ['required' => false])
            ->add('vrCredito',NumberType::class,['required' => false])
            ->add('vrCuota',NumberType::class,['required' => false])
            ->add('numeroCuotaActual',NumberType::class,['required' => false])
            ->add('validarCuotas',CheckboxType::class,['required' => false])
            ->add('aplicarCuotaPrima',CheckboxType::class,['required' => false])
            ->add('inactivoPeriodo',CheckboxType::class,['required' => false])
            ->add('aplicarCuotaCesantia',CheckboxType::class,['required' => false])
            ->add('creditoPagoTipoRel',EntityType::class,[
                'class' => RhuCreditoPagoTipo::class,
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('cpt')
                        ->orderBy('cpt.nombre');
                },'required' => true,
                'choice_label' => 'nombre'
            ])
            ->add('creditoTipoRel',EntityType::class,[
                'class' => RhuCreditoTipo::class,
                'query_builder' => function (EntityRepository $er){
                    return $er->createQueryBuilder('er')
                        ->orderBy('er.nombre','ASC');
                },'required' => true,
                'choice_label' => 'nombre'
            ])
            ->add('guardar', SubmitType::class, ['attr' => ['class' => 'btn btn-sm btn-primary']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RhuCredito::class,
        ]);
    }

    public function getEstructuraPropiedadesLista()
    {
        $campos = '[
            {"campo":"codigoCreditoPk"                 ,"tipo":"pk"     ,"ayuda":"Codigo del registro"                   ,"titulo":"ID"},
            {"campo":"creditoTipoRel.nombre"           ,"tipo":"texto"  ,"ayuda":"Tipo de credito"                       ,"titulo":"TIPO"           ,"relacion":"SI"},
            {"campo":"codigoEmpleadoFk"                ,"tipo":"texto"  ,"ayuda":"Codigo del empleado"                   ,"titulo":"CODIGO"},
            {"campo":"empleadoRel.numeroIdentificacion","tipo":"texto"  ,"ayuda":"Numero de identificacion del empleado" ,"titulo":"IDENTIFICACION" ,"relacion":"SI"},
            {"campo":"empleadoRel.nombreCorto"         ,"tipo":"texto"  ,"ayuda":"Nombre del empleado"                   ,"titulo":"EMPLEADO"         ,"relacion":"SI"},            
            {"campo":"empleadoRel.estadoContrato"      ,"tipo":"bool"   ,"ayuda":"Si el empleado se encuentra contratado","titulo":"CON"            ,"relacion":"SI"},
            {"campo":"grupoRel.nombre"                 ,"tipo":"texto"  ,"ayuda":"Nombre del grupo del empleado"         ,"titulo":"GRUPO"          ,"relacion":"SI"},
            {"campo":"fecha"                           ,"tipo":"fecha"  ,"ayuda":"Fecha"                                 ,"titulo":"FECHA"},
            {"campo":"vrCredito"                       ,"tipo":"moneda" ,"ayuda":"Valor del credito"                     ,"titulo":"CREDITO"},            
            {"campo":"vrCuota"                         ,"tipo":"moneda" ,"ayuda":"Valor de la cuota"                     ,"titulo":"V.CUOTA"},
            {"campo":"numeroCuotaActual"               ,"tipo":"text"   ,"ayuda":"Numero de la cuota actual"             ,"titulo":"C_ACTUAL"},
            {"campo":"numeroCuotas"                    ,"tipo":"texto"  ,"ayuda":"Cantidad de cuotas"                    ,"titulo":"CUOTAS"},
            {"campo":"vrAbonos"                        ,"tipo":"moneda" ,"ayuda":"Total de abonos"                     ,"titulo":"ABONOS"},
            {"campo":"vrSaldo"                         ,"tipo":"moneda" ,"ayuda":"Saldo por pagar"                     ,"titulo":"SALDO"},
            {"campo":"estadoPagado"                    ,"tipo":"bool"   ,"ayuda":"Estado pagado"                         ,"titulo":"PAG"},
            {"campo":"estadoSuspendido"                ,"tipo":"bool"   ,"ayuda":"Estado suspendido"                     ,"titulo":"SUS"},
            {"campo":"inactivoPeriodo"                ,"tipo":"bool"   ,"ayuda":"Inactivo por un periodo de nomina"     ,"titulo":"INP"}
            
            ]';
        return $campos;
    }

    public function getEstructuraPropiedadesFiltro()
    {
        $campos = '[
            {"child":"codigoCreditoPk", "tipo":"TextType",   "propiedades":{"label":"Codigo"}},
            {"child":"codigoCreditoTipoFk",       "tipo":"EntityType", "propiedades":{"class":"RhuCreditoTipo","choice_label":"nombre","label":"Tipo"}},
            {"child":"codigoEmpleadoFk",    "tipo":"TextType",   "propiedades":{"label":"Empleado"}},
            {"child":"fechaDesde",          "tipo":"DateType",   "propiedades":{"label":"Fecha Desde"}},
            {"child":"fechaHasta",          "tipo":"DateType",   "propiedades":{"label":"Fecha Hasta"}},
            {"child":"estadoPagado",    "tipo":"ChoiceType", "propiedades":{"label":"Autorizado",     "choices":{"SI":true,"NO":false}}},
            {"child":"estadoSuspendido",      "tipo":"ChoiceType", "propiedades":{"label":"Aprobado",       "choices":{"SI":true,"NO":false}}}
        ]';

        return $campos;
    }
}
