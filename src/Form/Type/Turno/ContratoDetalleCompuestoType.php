<?php


namespace App\Form\Type\Turno;


use App\Entity\Turno\TurConcepto;
use App\Entity\Turno\TurContratoDetalleCompuesto;
use App\Entity\Turno\TurModalidad;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContratoDetalleCompuestoType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('conceptoRel', EntityType::class, array(
                'class' => TurConcepto::class,
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('cs')
                        ->orderBy('cs.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => true))
            ->add('modalidadRel', EntityType::class, array(
                'class' => TurModalidad::class,
                'query_builder' => function (EntityRepository $er)  {
                    return $er->createQueryBuilder('ms')
                        ->orderBy('ms.nombre', 'ASC');},
                'choice_label' => 'nombre',
                'required' => true))
            ->add('cantidad', NumberType::class)
            ->add('lunes', CheckboxType::class, array('required'  => false))
            ->add('martes', CheckboxType::class, array('required'  => false))
            ->add('miercoles', CheckboxType::class, array('required'  => false))
            ->add('jueves', CheckboxType::class, array('required'  => false))
            ->add('viernes', CheckboxType::class, array('required'  => false))
            ->add('sabado', CheckboxType::class,  array('required'  => false))
            ->add('domingo', CheckboxType::class, array('required'  => false))
            ->add('festivo', CheckboxType::class, array('required'  => false))
            ->add('dia31', CheckboxType::class, array('required'  => false))
            ->add('DiasReales', CheckboxType::class, array('required'  => false))
            ->add('guardar', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TurContratoDetalleCompuesto::class,
        ]);
    }
}