<?php

namespace App\Form\Type\RecursoHumano;

use App\Entity\RecursoHumano\RhuAporte;
use App\Entity\RecursoHumano\RhuAporteTipo;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AporteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('aporteTipoRel',EntityType::class,[
                'class' => RhuAporteTipo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('at')
                        ->orderBy('at.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'required' => true,
            ])
            ->add('sucursalRel',EntityType::class,[
                'required' => true,
                'class' => 'App\Entity\RecursoHumano\RhuSucursal',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('s')
                        ->orderBy('s.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Sucursal:'
            ])
            ->add('anio', TextType::class, array('required' => true))
            ->add('mes', ChoiceType::class, [
                'choices' => array(
                    'Enero' => '01', 'Febrero' => '02', 'Marzo' => '03', 'Abril' => '04', 'Mayo' => '05', 'Junio' => '06', 'Julio' => '07',
                    'Agosto' => '08', 'Septiembre' => '09', 'Octubre' => '10', 'Noviembre' => '11', 'Diciembre' => '12',
                ),
                'required'    => true,
            ])
            ->add('formaPresentacion',ChoiceType::class,['choices' => ['SUCURSAL' => 'S','UNICA' => 'U']])
            ->add('guardar',SubmitType::class,['label' => 'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RhuAporte::class,
        ]);
    }

}