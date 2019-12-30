<?php

namespace App\Form\Type\General;

use App\Entity\General\GenBanco;
use App\Entity\General\GenCuenta;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CuentaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoCuentaPk', TextType::class, ['required' => true, 'label' => 'Código:'])
            ->add('nombre', TextType::class, ['required' => true, 'label' => 'Nombre:'])
            ->add('cuenta', TextType::class, ['required' => true,'label' => 'Cuenta:'])
            ->add('tipo', TextType::class, ['required' => true,'label' => 'Tipo:'])
            ->add('codigoCuentaContableFk', TextType::class, ['required' => true,'label' => 'Cuenta contable:'])
            ->add('bancoRel',EntityType::class,[
                'class' => GenBanco::class,
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('er')
                        ->orderBy('er.nombre');
                },'choice_label' => 'nombre',
                'required' => true,
                'label' => 'Banco:'
            ])
            ->add('guardar',SubmitType::class,['label' => 'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => GenCuenta::class,
        ]);
    }

}
