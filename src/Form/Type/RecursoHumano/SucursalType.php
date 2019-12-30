<?php


namespace App\Form\Type\RecursoHumano;


use App\Entity\RecursoHumano\RhuRequisitoTipo;
use App\Entity\RecursoHumano\RhuSucursal;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SucursalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigoSucursalPk', TextType::class,['required'=>true])
            ->add('nombre', TextType::class,['required'=>true])
            ->add('codigo', TextType::class,['required'=>true])
            ->add('estadoActivo', CheckboxType::class, ['required' => false])
            ->add('guardar', SubmitType::class, ['label'=>'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RhuSucursal::class,
        ]);
    }

}