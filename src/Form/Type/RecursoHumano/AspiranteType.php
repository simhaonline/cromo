<?php

namespace App\Form\Type\RecursoHumano;

use App\Entity\RecursoHumano\RhuAspirante;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AspiranteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoIdentificacionFk')
            ->add('codigoEstadoCivilFk')
            ->add('codigoCiudadFk')
            ->add('codigoCiudadExpedicionFk')
            ->add('codigoRhFk')
            ->add('fecha')
            ->add('numeroIdentificacion')
            ->add('libretaMilitar')
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
            ->add('codigoCiudadNacimientoFk')
            ->add('peso')
            ->add('estatura')
            ->add('cargoAspira')
            ->add('recomendado')
            ->add('reintegro')
            ->add('codigoCargoFk')
            ->add('estadoAprobado')
            ->add('estadoCerrado')
            ->add('estadoAutorizado')
            ->add('estadoBloqueado')
            ->add('genIdentificacionRel')
            ->add('genEstadoCivilRel')
            ->add('genCiudadRel')
            ->add('genCiudadExpedicionRel')
            ->add('genCiudadNacimientoRel')
            ->add('rhRel')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RhuAspirante::class,
        ]);
    }
}
