<?php


namespace App\Form\Type\RecursoHumano;


use App\Entity\RecursoHumano\RhuAcademia;
use App\Entity\RecursoHumano\RhuAcreditacion;
use App\Entity\RecursoHumano\RhuAcreditacionTipo;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AcreditacionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fechaVenceCurso', DateType::class, array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date')))
            ->add('codigoAcreditacionTipoFk', EntityType::class, [
                'class' => RhuAcreditacionTipo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('at')
                        ->orderBy('at.codigoAcreditacionTipoPk', 'ASC');
                },
                'required' => false,
                'choice_label' => 'nombre',
                'placeholder' => 'TODOS'
            ])
            ->add('fechaVencimiento', DateType::class, array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date')))
            ->add('codigoAcademiaFk', EntityType::class, [
                'class' => RhuAcademia::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('a')
                        ->orderBy('a.codigoAcademiaPk', 'ASC');
                },
                'required' => false,
                'choice_label' => 'nombre',
                'placeholder' => 'TODOS'
            ])
            ->add('codigoEmpleadoFk', TextType::class, array('required' => false))
            ->add('numeroRegistro', TextType::class, array('required' => false))
            ->add('codigoAcreditacionRechazoFk', IntegerType::class, array('required' => false))
            ->add('comentarios', TextareaType::class, array('required' => false))
            ->add('motivoRechazo', TextareaType::class, array('required' => false))
            ->add('estadoRechazado', CheckboxType::class,  array('required' => false))
            ->add('guardar', SubmitType::class, ['label' => 'guardar', 'attr' => ['class' => 'btn btn-sm btn-primary']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RhuAcreditacion::class,
        ]);
    }
}