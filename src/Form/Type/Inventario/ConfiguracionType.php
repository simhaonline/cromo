<?php

namespace App\Form\Type\Inventario;

use App\Entity\Inventario\InvConfiguracion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ConfiguracionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoConfiguracionPk')
            ->add('informacionLegalMovimiento')
            ->add('informacionPagoMovimiento')
            ->add('informacionContactoMovimiento')
            ->add('informacionResolucionDianMovimiento')
            ->add('codigoFormatoMovimiento')
            ->add('codigoDocumentoMovimientosSalidaBodega')
            ->add('codigoDocumentoMovimientosEntradaBodega')
            ->add('guardar',SubmitType::class)
            ->add('guardarnuevo', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => InvConfiguracion::class,
        ]);
    }
}
