<?php

namespace App\Form\Type\Turno;

use App\Entity\Turno\TurCotizacion;
use App\Entity\Turno\TurPedido;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CotizacionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('sectorRel', EntityType::class, [
                'required' => true,
                'class' => 'App\Entity\Turno\TurSector',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('s')
                        ->orderBy('s.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'nombre:'
            ])
            ->add('cotizacionTipoRel', EntityType::class, [
                'required' => true,
                'class' => 'App\Entity\Turno\TurCotizacionTipo',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('ct')
                        ->orderBy('ct.codigoCotizacionTipoPk', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Tipo pedido:'
            ])
            ->add('estrato', NumberType::class, array('required' => false))
            ->add('fechaVence', DateType::class, ['label' => 'Fecha desde: ',  'required' => false, 'widget' => 'single_text', 'format' => 'yyyy-MM-dd'])
            ->add('vrSalarioBase', NumberType::class)
            ->add('comentario', TextareaType::class, array('required' => false))
            ->add('guardar', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TurCotizacion::class,
        ]);
    }

}
