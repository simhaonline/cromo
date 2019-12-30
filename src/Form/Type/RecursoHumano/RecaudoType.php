<?php

namespace App\Form\Type\RecursoHumano;

use App\Entity\RecursoHumano\RhuRecaudo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecaudoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fecha')
            ->add('codigoEntidadSaludFk')
            ->add('numero')
            ->add('comentarios')
            ->add('vrTotal')
            ->add('estadoAutorizado')
            ->add('fechaPago')
            ->add('estadoAprobado')
            ->add('estadoAnulado')
            ->add('reciboCaja')
            ->add('ValorReciboCaja')
            ->add('estadoCerrado')
            ->add('vrTotalEntidad')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RhuRecaudo::class,
        ]);
    }

}
