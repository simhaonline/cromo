<?php

namespace App\Form\Type\RecursoHumano;

use App\Entity\RecursoHumano\RhuPago;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PagoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoPagoTipoFk')
            ->add('codigoPeriodoFk')
            ->add('numero')
            ->add('codigoEmpleadoFk')
            ->add('codigoContratoFk')
            ->add('codigoGrupoFk')
            ->add('codigoProgramacionFk')
            ->add('codigoProgramacionDetalleFk')
            ->add('codigoVacacionFk')
            ->add('codigoLiquidacionFk')
            ->add('codigoEntidadSaludFk')
            ->add('codigoEntidadPensionFk')
            ->add('fechaDesde')
            ->add('fechaHasta')
            ->add('fechaDesdeContrato')
            ->add('fechaHastaContrato')
            ->add('vrSalarioContrato')
            ->add('vrDevengado')
            ->add('vrDeduccion')
            ->add('vrAuxilioTransporte')
            ->add('vrIngresoBaseCotizacion')
            ->add('vrIngresoBasePrestacion')
            ->add('estadoAutorizado')
            ->add('estadoAprobado')
            ->add('estadoAnulado')
            ->add('comentario')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RhuPago::class,
        ]);
    }

}
