<?php

namespace App\Form\Type\RecursoHumano;

use App\Entity\RecursoHumano\RhuCredito;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreditoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoCreditoTipoFk')
            ->add('codigoCreditoTipoPagoFk')
            ->add('codigoEmpleadoFk')
            ->add('codigoCentroCostoFk')
            ->add('codigoProgramacionDetalleFk')
            ->add('fecha')
            ->add('fechaInicio')
            ->add('fechaFinalizacion')
            ->add('fechaCredito')
            ->add('vrInicial')
            ->add('vrPagar')
            ->add('vrCuota')
            ->add('vrCuotaPrima')
            ->add('vrCuotaTemporal')
            ->add('saldo')
            ->add('saldoTotal')
            ->add('numeroCuotas')
            ->add('numeroCuotaActual')
            ->add('comentarios')
            ->add('seguro')
            ->add('estadoSuspendido')
            ->add('estadoPagado')
            ->add('estadoAutorizado')
            ->add('estadoAprobado')
            ->add('estadoAnulado')
            ->add('vrAbonos')
            ->add('numeroLibranza')
            ->add('usuario')
            ->add('totalPagos')
            ->add('validarCuotas')
            ->add('aplicarCuotaPrima')
            ->add('aplicarCuotaCesantia')
        ;
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
            {"campo":"codigoCreditoPk",    "tipo":"pk"     ,"ayuda":"Codigo del registro" ,"titulo":"ID"},
            {"campo":"codigoCreditoTipoFk","tipo":"texto"  ,"ayuda":"Tipo de credito"     ,"titulo":"TIPO"},
            {"campo":"codigoEmpleadoFk",   "tipo":"texto"  ,"ayuda":"Codigo del empleado" ,"titulo":"EMPLEADO"},
            {"campo":"fecha",              "tipo":"fecha"  ,"ayuda":"Fecha"               ,"titulo":"FECHA"},                     
            {"campo":"vrCuota",            "tipo":"moneda" ,"ayuda":"Valor de la cuota"   ,"titulo":"V.CUOTA"},                     
            {"campo":"numeroCuotas",       "tipo":"texto"  ,"ayuda":"Cantidad de cuotas"  ,"titulo":"CUOTAS"},                     
            {"campo":"estadoPagado",       "tipo":"bool"   ,"ayuda":"Estado pagado"       ,"titulo":"PAG"},                     
            {"campo":"estadoSuspendido",   "tipo":"bool"   ,"ayuda":"Estado suspendido"   ,"titulo":"SUS"}                                          
        ]';
        return $campos;
    }
}
