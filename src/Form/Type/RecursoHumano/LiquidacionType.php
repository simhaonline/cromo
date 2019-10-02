<?php

namespace App\Form\Type\RecursoHumano;

use App\Entity\RecursoHumano\RhuLiquidacion;
use App\Entity\RecursoHumano\RhuLiquidacionTipo;
use App\Entity\RecursoHumano\RhuVacacionTipo;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LiquidacionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('liquidacionTipoRel',EntityType::class,[
                'class' => RhuLiquidacionTipo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('lt')
                        ->orderBy('lt.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'required' => true,
            ])
            ->add('fecha')
            ->add('numero')
            ->add('codigoEmpleadoFk')
            ->add('codigoContratoFk')
            ->add('codigoGrupoFk')
            ->add('codigoContratoMotivoFk')
            ->add('fechaDesde')
            ->add('fechaHasta')
            ->add('vrCesantias')
            ->add('vrInteresesCesantias')
            ->add('vrPrima')
            ->add('vrVacacion')
            ->add('vrIndemnizacion')
            ->add('diasCesantias')
            ->add('diasCesantiasAusentismo')
            ->add('diasVacacion')
            ->add('diasVacacionAusentismo')
            ->add('diasPrima')
            ->add('diasPrimaAusentismo')
            ->add('fechaUltimoPagoPrima')
            ->add('fechaUltimoPagoVacacion')
            ->add('fechaUltimoPagoCesantias')
            ->add('estadoAutorizado')
            ->add('estadoAprobado')
            ->add('estadoAnulado');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RhuLiquidacion::class,
        ]);
    }
}
