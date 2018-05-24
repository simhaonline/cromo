<?php

namespace App\Form\Type\RecursoHumano;

use App\Entity\RecursoHumano\RhuSolicitud;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SolicitudType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoGrupoPagoFk')
            ->add('codigoCodigoCargoFk')
            ->add('codigoExperienciaSolicitudFk')
            ->add('codigoEstadoCivilFk')
            ->add('codigoCiudadFk')
            ->add('codigoEstudioTipoFk')
            ->add('codigoClasificacionRiesgoFk')
            ->add('disponbilidad')
            ->add('fecha')
            ->add('fechaContratacion')
            ->add('fechaVencimiento')
            ->add('nombre')
            ->add('cantidadSolicitada')
            ->add('VrSalario')
            ->add('VrNoSalarial')
            ->add('salarioFijo')
            ->add('salarioVariable')
            ->add('fechaPruebas')
            ->add('edadMinima')
            ->add('edadMaxima')
            ->add('numeroHijos')
            ->add('codigoLicenciaCarroFk')
            ->add('codigoLicenciaMotoFk')
            ->add('experienciaSolicitud')
            ->add('vehiculo')
            ->add('comentarios')
            ->add('estadoAutorizado')
            ->add('estadoAprobado')
            ->add('estadoCerrado')
            ->add('codigoUsuario')
            ->add('grupoPagoRel')
            ->add('cargoRel')
            ->add('solicitudExperienciaRel')
            ->add('genEstadoCivilRel')
            ->add('genCiudadRel')
            ->add('genEstudioTipoRel')
            ->add('clasificacionRiesgoRel')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RhuSolicitud::class,
        ]);
    }
}
