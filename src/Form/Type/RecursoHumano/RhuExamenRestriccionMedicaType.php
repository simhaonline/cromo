<?php


namespace App\Form\Type\RecursoHumano;


use App\Entity\RecursoHumano\RhuExamenRestriccionMedica;
use App\Entity\RecursoHumano\RhuExamenRevisionMedicaTipo;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RhuExamenRestriccionMedicaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('examenRevisionMedicaTipoRel', EntityType::class, [
                'class' => RhuExamenRevisionMedicaTipo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('rmt')
                        ->orderBy('rmt.codigoExamenRevisionMedicaTipoPk', 'ASC');
                },
                'choice_label' => 'nombre',
            ])
            ->add('dias', NumberType::class, array('required' => true))
            ->add('guardar',SubmitType::class,['attr' => ['class' => 'btn btn-sm btn-primary']]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RhuExamenRestriccionMedica::class,
        ]);
    }

}