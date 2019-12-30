<?php

namespace App\Form\Type\Transporte;
use App\Entity\Transporte\TteCliente;
use App\Entity\Transporte\TteGuiaCliente;
use App\Entity\Transporte\TteGuiaTipo;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GuiaClienteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('guiaTipoRel', EntityType::class, array(
                'class' =>TteGuiaTipo::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('gt')
                        ->orderBy('gt.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Tipo:'
            ))
            ->add('clienteRel', EntityType::class, array(
                'class' => TteCliente::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.nombreCorto', 'ASC');
                },
                'choice_label' => 'nombreCorto',
                'label' => 'Cliente:'
            ))
            ->add('desde',NumberType::class,['required' => true,'label' => 'Desde:'])
            ->add('hasta',NumberType::class,['required' => true,'label' => 'Hasta:'])
            ->add('estadoActivo', CheckboxType::class, array('required'  => false))
            ->add('guardar', SubmitType::class, ['label'=>'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TteGuiaCliente::class,
        ]);
    }

}
