<?php

namespace App\Form\Type\RecursoHumano;

use App\Entity\RecursoHumano\RhuSeleccion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SeleccionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoSeleccionTipoFk')
            ->add('codigoIdentificacionFk')
            ->add('codigoEstadoCivilFk')
            ->add('codigoGrupoPagoFk')
            ->add('codigoCiudadFk')
            ->add('codigoCiudadNacimientoFk')
            ->add('codigoCiudadExpedicionFk')
            ->add('codigoRhFk')
            ->add('codigoSolicitudFk')
            ->add('codigoCargoFk')
            ->add('codigoCierreSeleccionMotivoFk')
            ->add('fecha')
            ->add('numeroIdentificacion')
            ->add('nombreCorto')
            ->add('nombre1')
            ->add('nombre2')
            ->add('apellido1')
            ->add('apellido2')
            ->add('telefono')
            ->add('celular')
            ->add('direccion')
            ->add('barrio')
            ->add('correo')
            ->add('fechaNacimiento')
            ->add('comentarios')
            ->add('estadoAprobado')
            ->add('presentaPruebas')
            ->add('referenciasVerificadas')
            ->add('fechaEntrevista')
            ->add('fechaPrueba')
            ->add('estadoAutorizado')
            ->add('fechaCierre')
            ->add('seleccionTipoRel')
            ->add('genIdentificacionRel')
            ->add('genEstadoCivilRel')
            ->add('grupoPagoRel')
            ->add('genCiudadRel')
            ->add('genCiudadExpedicionRel')
            ->add('genCiudadNacimientoRel')
            ->add('rhRel')
            ->add('solicitudRel')
            ->add('cargoRel')
            ->add('cierreSeleccionMotivoRel')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RhuSeleccion::class,
        ]);
    }
}
