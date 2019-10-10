<?php

namespace App\Form\Type\Tesoreria;

use App\Entity\General\GenCuenta;
use App\Entity\Tesoreria\TesMovimiento;
use App\Entity\Tesoreria\TesMovimientoTipo;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MovimientoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('movimientoTipoRel', EntityType::class, [
                'class' => TesMovimientoTipo::class,
                'query_builder' => function (EntityRepository $er) use ($options) {
                    $clase = $options['data']->getMovimientoClaseRel()->getCodigoMovimientoClasePk();
                    return $er->createQueryBuilder('et')
                        ->where("et.codigoMovimientoClaseFk='{$clase}'")
                        ->orderBy('et.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'required' => true
            ])
            ->add('cuentaRel', EntityType::class, [
                'class' => GenCuenta::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.nombre');
                },
                'choice_label' => 'nombre'
            ])
            ->add('fecha', DateType::class, array('widget' => 'single_text', 'format' => 'yyyy-MM-dd'))
            ->add('comentarios', TextareaType::class, ['label' => 'Comentario:', 'required' => false])
            ->add('guardar', SubmitType::class, ['label' => 'Guardar', 'attr' => ['class' => 'btn btn-sm btn-primary']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TesMovimiento::class,
        ]);
    }

}