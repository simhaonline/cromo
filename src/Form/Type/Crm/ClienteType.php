<?php

namespace App\Form\Type\Crm;

use App\Entity\Crm\CrmCliente;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClienteType  extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('identificacionRel', EntityType::class, [
                'required' => false,
                'class' => 'App\Entity\General\GenIdentificacion',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('i')
                        ->orderBy('i.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'label' => 'Identificacion tipo:'
            ])
            ->add('ciudadRel', EntityType::class, [
                'class' => 'App\Entity\General\GenCiudad',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.nombre');
                },
                'choice_label' => 'nombre',
                'required' => true,
                'label' => 'Ciudad:',
                'attr' => ['class' => 'form-control to-select-2']
            ])
            ->add('numeroIdentificacion', TextType::class, ['required' => true, 'label' => 'Identificacion:'])
            ->add('digitoVerificacion', TextType::class, ['required' => false, 'label' => 'Digito:'])
            ->add('nombres', TextType::class, ['required' => false, 'label' => 'Nombres:'])
            ->add('nombreCorto', TextType::class, ['required' => true, 'label' => 'Nombre corto:'])
            ->add('apellido1', TextType::class, ['required' => false, 'label' => 'Primer apellido:'])
            ->add('apellido2', TextType::class, ['required' => false, 'label' => 'Segundo apellido:'])
            ->add('direccion', TextType::class, ['required' => true, 'label' => 'Direccion:'])
            ->add('telefono', TextType::class, ['required' => true, 'label' => 'Telefono:'])
            ->add('celular', TextType::class, ['required' => false, 'label' => 'Celular:'])
            ->add('email', TextType::class, ['required' => false, 'label' => 'Email:'])
            ->add('guardar', SubmitType::class, ['label' => 'Guardar', 'attr' => ['class' => 'btn btn-sm btn-primary']]);;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CrmCliente::class,
        ]);
    }

}
