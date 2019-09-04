<?php


namespace App\Form\Type\Tesoreria;


use App\Entity\Tesoreria\TesMovimiento;
use App\Entity\Tesoreria\TesMovimientoTipo;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MovimientoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('numero', IntegerType::class, ['required' => true , 'label' => 'Identificacion:'])
            ->add('fecha', DateType::class,array('widget' => 'single_text', 'format' => 'yyyy-MM-dd', 'attr' => array('class' => 'date',)))
            ->add('movimientoTipoRel', EntityType::class, [
                'class' => TesMovimientoTipo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('mt')
                        ->orderBy('mt.codigoMovimientoTipoPK', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Tipo cuenta:'
            ])
            ->add('guardar', SubmitType::class, ['label' => 'Guardar']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TesMovimiento::class,
        ]);
    }
}