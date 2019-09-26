<?php


namespace App\Form\Type\RecursoHumano;


use App\Entity\RecursoHumano\RhuAccidente;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AccidenteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
//            ->add('ciudadRel', EntityType::class, array(
//                'class' => 'BrasaGeneralBundle:GenCiudad',
//                'query_builder' => function (EntityRepository $er) {
//                    return $er->createQueryBuilder('c')
//                        ->orderBy('c.nombre', 'ASC');
//                },
//                'choice_label' => 'nombre',
//                'required' => false))
            ->add('codigoFurat', TextType::class, array('required' => false))
            ->add('fechaAccidente', DateType::class)
//            ->add('tipoAccidenteRel', EntityType::class, array(
//                'class' => 'BrasaRecursoHumanoBundle:RhuAccidenteTrabajoTipoAccidente',
//                'choice_label' => 'nombre',))
            ->add('fechaEnviaInvestigacion', DateType::class)
            ->add('fechaIncapacidadDesde', DateType::class)
            ->add('fechaIncapacidadHasta', DateType::class)
//            ->add('codigoAccidenteTrabajoNaturalezaLesionFk')
//            ->add('codigoAccidenteTrabajoCuerpoAfectadoFk')
//            ->add('codigoAccidenteTrabajoAgenteFk')
//            ->add('codigoAccidenteTrabajoMecanismoAccidenteFk')
            ->add('codigoEmpleadoFk',TextType::class)
            ->add('naturalezaLesion',TextType::class,[])
            ->add('cuerpoAfectado',TextType::class,[])
            ->add('agente',TextType::class,[])
            ->add('mecanismoAccidente',TextType::class,[])
            ->add('lugarAccidente', TextType::class, array('required' => false))
            ->add('coordinadorEncargado', TextType::class, array('required' => false))
            ->add('tiempoServicioEmpleado', TextType::class, array('required' => false))
            ->add('tareaDesarrolladaMomentoAccidente', ChoiceType::class, array('choices' => array('SI' => '1', 'NO' => '0')))
            ->add('oficioHabitual', TextType::class, array('required' => false))
            ->add('descripcionAccidente', TextareaType::class, array('required' => false))
            ->add('actoInseguro', TextareaType::class, array('required' => false))
            ->add('condicionInsegura', TextareaType::class, array('required' => false))
            ->add('factorPersonal', TextareaType::class, array('required' => false))
            ->add('factorTrabajo', TextareaType::class, array('required' => false))
            ->add('planAccion1', TextType::class, array('required' => false))
//            ->add('tipoControlUnoRel', EntityType::class, array(
//                'class' => 'BrasaRecursoHumanoBundle:RhuAccidenteTrabajoTipoControl',
//                'choice_label' => 'nombre',))
            ->add('fechaVerificacion1', DateType::class)
            ->add('areaResponsable1', TextType::class, array('required' => false))
            ->add('planAccion2', TextType::class, array('required' => false))
//            ->add('tipoControlDosRel', EntityType::class, array(
//                'class' => 'BrasaRecursoHumanoBundle:RhuAccidenteTrabajoTipoControl',
//                'choice_label' => 'nombre',))
            ->add('fechaVerificacion2', DateType::class)
            ->add('areaResponsable2', TextType::class, array('required' => false))
            ->add('planAccion3', TextType::class, array('required' => false))
//            ->add('tipoControlTresRel', EntityType::class, array(
//                'class' => 'BrasaRecursoHumanoBundle:RhuAccidenteTrabajoTipoControl',
//                'choice_label' => 'nombre',))
            ->add('fechaVerificacion3', DateType::class)
            ->add('areaResponsable3', TextType::class, array('required' => false))
            ->add('participanteInvestigacion1', TextType::class, array('required' => false))
            ->add('cargoParticipanteInvestigacion1', TextType::class, array('required' => false))
            ->add('participanteInvestigacion2', TextType::class, array('required' => false))
            ->add('cargoParticipanteInvestigacion2', TextType::class, array('required' => false))
            ->add('participanteInvestigacion3', TextType::class, array('required' => false))
            ->add('cargoParticipanteInvestigacion3', TextType::class, array('required' => false))
            ->add('representanteLegal', TextType::class, array('required' => false))
            ->add('cargoRepresentanteLegal', TextType::class, array('required' => false))
            ->add('documentoRepresentanteLegal', TextType::class, array('required' => false))
            ->add('licencia', TextType::class, array('required' => false))
            ->add('fechaVerificacion', DateType::class)
            ->add('responsableVerificacion', TextType::class, array('required' => false))
            ->add('guardar', SubmitType::class, ['label' => 'guardar', 'attr' => ['class' => 'btn btn-sm btn-primary']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RhuAccidente::class,
        ]);
    }
}