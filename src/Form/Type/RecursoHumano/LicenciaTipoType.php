<?php


namespace App\Form\Type\RecursoHumano;


use App\Entity\RecursoHumano\RhuLicenciaTipo;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LicenciaTipoType extends AbstractType
{

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RhuLicenciaTipo::class,
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre', TextType::class, ['required' => false,'label' => 'Nombre:'])
            ->add('afectaSalud', CheckboxType::class, array('required'  => false))
            ->add('ausentismo', CheckboxType::class, array('required'  => false))
            ->add('maternidad', CheckboxType::class, array('required'  => false))
            ->add('paternidad', CheckboxType::class, array('required'  => false))
            ->add('remunerada', CheckboxType::class, array('required'  => false))
            ->add('suspensionContratoTrabajo', CheckboxType::class, array('required'  => false))
            ->add('guardar', SubmitType::class, ['label' => 'Guardar','attr' => ['class' => 'btn btn-sm btn-primary']]);
        ;
    }

}