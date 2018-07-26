<?php

namespace App\Form\Type\Cartera;

use App\Entity\Cartera\CarRecibo;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ReciboType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('cuentaRel',EntityType::class,[
                'required' => false,
                'class' => 'App\Entity\General\GenCuenta',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Cuenta:'
            ])
            ->add('reciboTipoRel',EntityType::class,[
                'required' => false,
                'class' => 'App\Entity\Cartera\CarReciboTipo',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('rt')
                        ->orderBy('rt.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Tipo recibo:'
            ])
            ->add('clienteRel',EntityType::class,[
                'required' => false,
                'class' => 'App\Entity\Cartera\CarCliente',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cc')
                        ->orderBy('cc.nombreCorto', 'ASC');
                },
                'choice_label' => 'nombreCorto',
                'label' => 'Cliente:'
            ])
            ->add('fecha', DateType::class, array('format' => 'yyyyMMdd'))
            ->add('fechaPago', DateType::class, array('format' => 'yyyyMMdd'))
            ->add('comentarios',TextareaType::class,['required' => false,'label' => 'Comentarios:'])
            ->add('guardar', SubmitType::class, ['label'=>'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']])
            ->add('guardarnuevo', SubmitType::class, ['label'=>'Guardar y nuevo','attr' => ['class' => 'btn btn-sm btn-primary']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CarRecibo::class,
        ]);
    }
}
