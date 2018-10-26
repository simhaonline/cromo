<?php

namespace App\Form\Type\RecursoHumano;

use App\Entity\RecursoHumano\RhuCredito;
use App\Entity\RecursoHumano\RhuCreditoPago;
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
            ->add('comentarios', TextareaType::class, ['required' => false])
            ->add('vrSeguro',NumberType::class,['required' => false])
            ->add('vrPagar',NumberType::class,['required' => false])
            ->add('vrValorCuota',NumberType::class,['required' => false])
            ->add('numeroCuotaActual',NumberType::class,['required' => false])
            ->add('validarCuotas',CheckboxType::class,['required' => false])
            ->add('aplicarCuotaPrima',CheckboxType::class,['required' => false])
            ->add('inactivoPeriodo',CheckboxType::class,['required' => false])
            ->add('aplicarCuotaCesantia',CheckboxType::class,['required' => false])
            ->add('creditoPagoRel',EntityType::class,[
                'class' => RhuCreditoPago::class,
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('er')
                        ->orderBy('er.nombre');
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
            {"campo":"codigoCreditoPk",                 "tipo":"pk"     ,"ayuda":"Codigo del registro", "titulo":"ID",                               "mostrarExcel":"SI", "mostrarLista":"NO"},
            {"campo":"creditoTipoRel.nombre",           "tipo":"texto"  ,"ayuda":"Tipo de credito",     "titulo":"TIPO",                             "mostrarExcel":"SI", "mostrarLista":"NO", "relacion":"SI"},
            {"campo":"codigoEmpleadoFk",                "tipo":"texto"  ,"ayuda":"Codigo del empleado", "titulo":"COD.EMPLEADO",                     "mostrarExcel":"SI", "mostrarLista":"NO"},
            {"campo":"empleadoRel.nombreCorto",         "tipo":"texto"  ,"ayuda":"Nombre del empleado", "titulo":"NOMBRE",                           "mostrarExcel":"SI", "mostrarLista":"NO", "relacion":"SI"},
            {"campo":"empleadoRel.numeroIdentificacion","tipo":"texto"  ,"ayuda":"Numero de identificacion del empleado", "titulo":"IDENTIFICACION", "mostrarExcel":"SI", "mostrarLista":"NO", "relacion":"SI"},
            {"campo":"empleadoRel.estadoContrato",      "tipo":"bool"   ,"ayuda":"Si el empleado se encuentra contratado","titulo":"CON",            "mostrarExcel":"SI", "mostrarLista":"NO", "relacion":"SI"},
            {"campo":"grupoRel.nombre",                 "tipo":"texto"    ,"ayuda":"Nombre del grupo del empleado","titulo":"GRUPO",                 "mostrarExcel":"SI", "mostrarLista":"NO", "relacion":"SI"},
            {"campo":"fecha",                           "tipo":"fecha"  ,"ayuda":"Fecha",               "titulo":"FECHA",                            "mostrarExcel":"SI", "mostrarLista":"NO"},
            {"campo":"vrCuota",                         "tipo":"moneda" ,"ayuda":"Valor de la cuota",   "titulo":"V.CUOTA",                          "mostrarExcel":"SI", "mostrarLista":"NO"},
            {"campo":"numeroCuotaActual",               "tipo":"text"   ,"ayuda":"Numero de la cuota actual",   "titulo":"CUOTA ACTUAL",             "mostrarExcel":"SI", "mostrarLista":"NO"},
            {"campo":"numeroCuotas",                    "tipo":"texto"  ,"ayuda":"Cantidad de cuotas",  "titulo":"CUOTAS",                           "mostrarExcel":"SI", "mostrarLista":"NO"},
            {"campo":"estadoPagado",                    "tipo":"bool"   ,"ayuda":"Estado pagado",       "titulo":"PAG",                              "mostrarExcel":"SI", "mostrarLista":"NO"},
            {"campo":"estadoSuspendido",                "tipo":"bool"   ,"ayuda":"Estado suspendido",   "titulo":"SUS",                              "mostrarExcel":"SI", "mostrarLista":"NO"} 
        ]';
        return $campos;
    }
}
