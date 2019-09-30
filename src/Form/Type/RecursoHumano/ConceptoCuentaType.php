<?php

namespace App\Form\Type\RecursoHumano;

use App\Entity\General\GenCiudad;
use App\Entity\RecursoHumano\RhuCargo;
use App\Entity\RecursoHumano\RhuCentroTrabajo;
use App\Entity\RecursoHumano\RhuClasificacionRiesgo;
use App\Entity\RecursoHumano\RhuConceptoCuenta;
use App\Entity\RecursoHumano\RhuContrato;
use App\Entity\RecursoHumano\RhuContratoTipo;
use App\Entity\RecursoHumano\RhuCostoClase;
use App\Entity\RecursoHumano\RhuCostoGrupo;
use App\Entity\RecursoHumano\RhuEntidad;
use App\Entity\RecursoHumano\RhuGrupo;
use App\Entity\RecursoHumano\RhuPension;
use App\Entity\RecursoHumano\RhuSalarioTipo;
use App\Entity\RecursoHumano\RhuSalud;
use App\Entity\RecursoHumano\RhuSubtipoCotizante;
use App\Entity\RecursoHumano\RhuSucursal;
use App\Entity\RecursoHumano\RhuTiempo;
use App\Entity\RecursoHumano\RhuTipoCotizante;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConceptoCuentaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('costoClaseRel', EntityType::class, [
                'class' => RhuCostoClase::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('r')
                        ->orderBy('r.orden', 'ASC');
                },
                'choice_label' => 'nombre',
                'required' => true
            ])
            ->add('naturaleza', ChoiceType::class, array('choices'   => array('DEBITO' => 'D', 'CREDITO' => 'C') ,'required' => true))
            ->add('codigoCuentaFk',TextType::class,['required' => true])
            ->add('guardar', SubmitType::class, ['label' => 'Guardar', 'attr' => ['class' => 'btn btn-sm btn-primary']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RhuConceptoCuenta::class,
        ]);
    }
}
