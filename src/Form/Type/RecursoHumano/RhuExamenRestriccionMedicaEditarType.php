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

class RhuExamenRestriccionMedicaEditarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('examenRevisionMedicaTipoRel', EntityType::class, [
                'class' => RhuExamenRevisionMedicaTipo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('o')
                        ->orderBy('o.codigoExamenRevisionMedicaTipoPk', 'ASC');
                },
                'required' => false,
                'choice_label' => 'nombre',
                'placeholder' => 'TODOS',
            ])
            ->add('dias', NumberType::class, array('required' => true))
            ->add('eliminar', SubmitType::class, array('label'  => 'Eliminar'))
            ->add('guardar', SubmitType::class, array('label'  => 'Guardar'));
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RhuExamenRestriccionMedica::class,
        ]);
    }

}