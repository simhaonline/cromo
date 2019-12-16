<?php


namespace App\Form\Type\Turno;


use App\Entity\Turno\TurNovedad;
use App\Entity\Turno\TurNovedadTipo;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NovedadType extends   AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('novedadTipoRel', EntityType::class, array(
                'class' => TurNovedadTipo::class,
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('nt')
                        ->orderBy('nt.codigoNovedadTipoPk', 'ASC');},
                'choice_label' => 'nombre',
                'required' => true))
            ->add('fechaDesde', DateType::class, ['label' => 'Fecha desde: ',  'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd'])
            ->add('fechaHasta', DateType::class, ['label' => 'Fecha hasta: ', 'required' => false,  'widget' => 'single_text', 'format' => 'yyyy-MM-dd'])
            ->add('comentarios', TextareaType::class, array('required' => false))
            ->add('guardar', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TurNovedad::class,
        ]);
    }

}